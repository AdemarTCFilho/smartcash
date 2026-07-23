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
            SELECT COALESCE(SUM(valorRecebido), 0) as total
            FROM contas_receber
            WHERE status = 'liquidado'
                AND dataRecebimento BETWEEN ? AND ?
        ", [$dataInicio, $dataFim])->row();

        $saidas = $this->db->query("
            SELECT COALESCE(SUM(valorPago), 0) as total
            FROM contas_pagar
            WHERE status = 'liquidado'
                AND dataPagamento BETWEEN ? AND ?
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
            SELECT DATE_FORMAT(dataRecebimento, '%Y-%m') as mes,
                   COALESCE(SUM(valorRecebido), 0) as total
            FROM contas_receber
            WHERE status = 'liquidado'
                AND dataRecebimento >= ?
            GROUP BY DATE_FORMAT(dataRecebimento, '%Y-%m')
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
            SELECT DATE_FORMAT(dataRecebimento, '%Y-%m') as mes,
                   COALESCE(SUM(valorRecebido), 0) as total
            FROM contas_receber
            WHERE status = 'liquidado'
                AND dataRecebimento >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)
                AND dataRecebimento < DATE_FORMAT(CURDATE(), '%Y-%m-01')
            GROUP BY DATE_FORMAT(dataRecebimento, '%Y-%m')
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
            SELECT COALESCE(SUM(valorRecebido), 0) as total
            FROM contas_receber
            WHERE status = 'liquidado'
                AND DATE_FORMAT(dataRecebimento, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
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
                unidade.nomeUnidade,
                COALESCE(SUM(contas_receber.valorRecebido), 0) as receita,
                COALESCE(SUM(contas_pagar.valorPago), 0) as despesa
            FROM unidade
            LEFT JOIN contas_receber ON contas_receber.idUnidade = unidade.idUnidade
                AND contas_receber.status = 'liquidado'
                AND contas_receber.dataRecebimento BETWEEN ? AND ?
            LEFT JOIN contas_pagar ON contas_pagar.idUnidade = unidade.idUnidade
                AND contas_pagar.status = 'liquidado'
                AND contas_pagar.dataPagamento BETWEEN ? AND ?
            WHERE unidade.status = 1
            GROUP BY unidade.idUnidade
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
                SELECT dataRecebimento as data, valorRecebido as valor, 0 as pago
                FROM contas_receber WHERE status = 'liquidado'
                UNION ALL
                SELECT dataPagamento as data, 0 as recebido, valorPago as pago
                FROM contas_pagar WHERE status = 'liquidado'
            ) m
            WHERE DATE_FORMAT(data, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
        ")->row();

        $mesAnterior = $this->db->query("
            SELECT COALESCE(SUM(valor), 0) - COALESCE(SUM(pago), 0) as saldo
            FROM (
                SELECT dataRecebimento as data, valorRecebido as valor, 0 as pago
                FROM contas_receber WHERE status = 'liquidado'
                UNION ALL
                SELECT dataPagamento as data, 0 as recebido, valorPago as pago
                FROM contas_pagar WHERE status = 'liquidado'
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
                SELECT DATE_FORMAT(dataRecebimento, '%Y-%m') as mes,
                       SUM(valorRecebido) as saldo
                FROM contas_receber WHERE status = 'liquidado'
                GROUP BY DATE_FORMAT(dataRecebimento, '%Y-%m')
                UNION ALL
                SELECT DATE_FORMAT(dataPagamento, '%Y-%m') as mes,
                       -SUM(valorPago) as saldo
                FROM contas_pagar WHERE status = 'liquidado'
                GROUP BY DATE_FORMAT(dataPagamento, '%Y-%m')
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
