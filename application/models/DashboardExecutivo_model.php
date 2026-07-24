<?php
class DashboardExecutivo_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSaldoEmCaixa()
    {
        $this->db->select('COALESCE(SUM(saldoInicial), 0) as total');
        $this->db->from('contas_bancarias');
        return $this->db->get()->row();
    }

    public function getAPagarHoje()
    {
        $hoje = date('Y-m-d');
        $this->db->select('COALESCE(SUM(valor - valorPago), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_pagar');
        $this->db->where('status', 'pendente');
        $this->db->where('vencimento', $hoje);
        return $this->db->get()->row();
    }

    public function getLucroLiquido($dataInicio, $dataFim)
    {
        $entradas = $this->db->query("
            SELECT COALESCE(SUM(valor), 0) as total
            FROM contas_receber
            WHERE vencimento BETWEEN ? AND ?
        ", [$dataInicio, $dataFim])->row();

        $saidas = $this->db->query("
            SELECT COALESCE(SUM(valor), 0) as total
            FROM contas_pagar
            WHERE vencimento BETWEEN ? AND ?
        ", [$dataInicio, $dataFim])->row();

        $lucro = (float)$entradas->total - (float)$saidas->total;
        $margem = (float)$entradas->total > 0 ? round(($lucro / (float)$entradas->total) * 100, 1) : 0;

        return (object)[
            'lucro' => $lucro,
            'margem' => $margem,
            'entradas' => $entradas->total,
            'saidas' => $saidas->total,
        ];
    }

    public function getInadimplencia()
    {
        $this->db->select('
            COALESCE(SUM(valor - valorRecebido), 0) as totalVencido,
            COALESCE(SUM(valor), 0) as totalCarteira,
            COUNT(*) as qtdVencidos
        ');
        $this->db->from('contas_receber');
        $this->db->where('status', 'pendente');
        $this->db->where('vencimento <', date('Y-m-d'));
        return $this->db->get()->row();
    }

    public function getFaturamentoMensal($meses = 12)
    {
        $data = [];
        for ($i = $meses - 1; $i >= 0; $i--) {
            $mes = date('Y-m', strtotime("-$i months"));
            $data[$mes] = 0;
        }

        $result = $this->db->query("
            SELECT DATE_FORMAT(vencimento, '%Y-%m') as mes,
                   COALESCE(SUM(valor), 0) as total
            FROM contas_receber
            WHERE vencimento >= ?
            GROUP BY DATE_FORMAT(vencimento, '%Y-%m')
            ORDER BY mes ASC
        ", [date('Y-m-d', strtotime('-' . ($meses - 1) . ' months'))])->result();

        foreach ($result as $r) {
            $data[$r->mes] = (float)$r->total;
        }

        $labels = [];
        $values = [];
        foreach ($data as $mes => $total) {
            $parts = explode('-', $mes);
            $labels[] = $parts[1] . '/' . substr($parts[0], 2);
            $values[] = $total;
        }

        return (object)['labels' => $labels, 'values' => $values];
    }

    public function getProjetado30D()
    {
        $hoje = date('Y-m-d');
        $fim = date('Y-m-d', strtotime('+30 days'));

        $receitas = $this->db->query("
            SELECT COALESCE(SUM(valor - valorRecebido), 0) as total
            FROM contas_receber
            WHERE status = 'pendente'
                AND vencimento BETWEEN ? AND ?
        ", [$hoje, $fim])->row();

        $despesas = $this->db->query("
            SELECT COALESCE(SUM(valor - valorPago), 0) as total
            FROM contas_pagar
            WHERE status = 'pendente'
                AND vencimento BETWEEN ? AND ?
        ", [$hoje, $fim])->row();

        return (object)[
            'receitas' => $receitas->total,
            'despesas' => $despesas->total,
            'liquido' => (float)$receitas->total - (float)$despesas->total,
        ];
    }

    public function getAlertasCriticos()
    {
        return $this->db->query("
            SELECT
                contas_receber.*,
                clientes.nomeCliente,
                DATEDIFF(CURDATE(), contas_receber.vencimento) as diasAtraso
            FROM contas_receber
            JOIN clientes ON clientes.idClientes = contas_receber.idClientes
            WHERE contas_receber.status = 'pendente'
                AND contas_receber.vencimento < CURDATE()
            ORDER BY contas_receber.vencimento ASC
            LIMIT 20
        ")->result();
    }

    public function getMetaMensal()
    {
        $ultimos3 = $this->db->query("
            SELECT DATE_FORMAT(vencimento, '%Y-%m') as mes,
                   COALESCE(SUM(valor), 0) as total
            FROM contas_receber
            WHERE vencimento >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)
                AND vencimento < DATE_FORMAT(CURDATE(), '%Y-%m-01')
            GROUP BY DATE_FORMAT(vencimento, '%Y-%m')
            ORDER BY mes DESC
            LIMIT 3
        ")->result();

        if (count($ultimos3) === 0) {
            return (object)[
                'meta' => 0,
                'realizado' => 0,
                'percentual' => 0,
                'media' => 0,
            ];
        }

        $soma = 0;
        foreach ($ultimos3 as $r) $soma += (float)$r->total;
        $media = $soma / count($ultimos3);
        $meta = $media * 1.05;

        $mesAtual = $this->db->query("
            SELECT COALESCE(SUM(valor), 0) as total
            FROM contas_receber
            WHERE DATE_FORMAT(vencimento, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
        ")->row();

        $realizado = (float)$mesAtual->total;
        $percentual = $meta > 0 ? round(($realizado / $meta) * 100, 1) : 0;

        return (object)[
            'meta' => $meta,
            'realizado' => $realizado,
            'percentual' => $percentual,
            'media' => $media,
        ];
    }

    public function getResultadoPorUnidade($dataInicio, $dataFim)
    {
        return $this->db->query("
            SELECT
                u.nomeUnidade,
                COALESCE((
                    SELECT SUM(cr.valor)
                    FROM contas_receber cr
                    WHERE cr.idUnidade = u.idUnidade
                      AND cr.vencimento BETWEEN ? AND ?
                ), 0) as receita,
                COALESCE((
                    SELECT SUM(cp.valor)
                    FROM contas_pagar cp
                    WHERE cp.idUnidade = u.idUnidade
                      AND cp.vencimento BETWEEN ? AND ?
                ), 0) as despesa
            FROM unidade u
            WHERE u.status = 1
            GROUP BY u.idUnidade
            ORDER BY receita DESC
        ", [$dataInicio, $dataFim, $dataInicio, $dataFim])->result();
    }

    public function getInfosGrupo()
    {
        $unidades = $this->db->query("SELECT COUNT(*) as qtd FROM unidade WHERE status = 1")->row();
        $empresas = $this->db->query("SELECT COUNT(*) as qtd FROM empresa WHERE status = 1")->row();
        return (object)[
            'unidades' => $unidades->qtd,
            'empresas' => $empresas->qtd,
        ];
    }

    public function getSaldoComparativo()
    {
        $mesAtual = $this->db->query("
            SELECT COALESCE(SUM(valor), 0) - COALESCE(SUM(pago), 0) as saldo
            FROM (
                SELECT vencimento as data, valor as valor, 0 as pago
                FROM contas_receber
                UNION ALL
                SELECT vencimento as data, 0 as recebido, valor as pago
                FROM contas_pagar
            ) m
            WHERE DATE_FORMAT(data, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
        ")->row();

        $mesAnterior = $this->db->query("
            SELECT COALESCE(SUM(valor), 0) - COALESCE(SUM(pago), 0) as saldo
            FROM (
                SELECT vencimento as data, valor as valor, 0 as pago
                FROM contas_receber
                UNION ALL
                SELECT vencimento as data, 0 as recebido, valor as pago
                FROM contas_pagar
            ) m
            WHERE DATE_FORMAT(data, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m')
        ")->row();

        $atual = (float)$mesAtual->saldo;
        $anterior = (float)$mesAnterior->saldo;
        $variacao = $anterior > 0 ? round((($atual - $anterior) / $anterior) * 100, 1) : 0;

        return (object)[
            'atual' => $atual,
            'anterior' => $anterior,
            'variacao' => $variacao,
        ];
    }

    public function getSaldoMensalUltimosMeses($meses = 6)
    {
        $data = array_fill_keys(array_map(function ($i) {
            return date('Y-m', strtotime("-$i months"));
        }, range($meses - 1, 0)), 0);
        ksort($data);

        $result = $this->db->query("
            SELECT mes, SUM(saldo) as total FROM (
                SELECT DATE_FORMAT(vencimento, '%Y-%m') as mes,
                       SUM(valor) as saldo
                FROM contas_receber
                GROUP BY DATE_FORMAT(vencimento, '%Y-%m')
                UNION ALL
                SELECT DATE_FORMAT(vencimento, '%Y-%m') as mes,
                       -SUM(valor) as saldo
                FROM contas_pagar
                GROUP BY DATE_FORMAT(vencimento, '%Y-%m')
            ) m
            GROUP BY mes
            ORDER BY mes ASC
        ")->result();

        foreach ($result as $r) {
            if (isset($data[$r->mes])) $data[$r->mes] = (float)$r->total;
        }

        return array_values($data);
    }
}
