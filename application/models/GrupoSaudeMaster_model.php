<?php
class GrupoSaudeMaster_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
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

    public function getReceitaConsolidada($dataInicio, $dataFim, $idEmpresa = null)
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) as total FROM contas_receber WHERE vencimento BETWEEN ? AND ?";
        $params = [$dataInicio, $dataFim];
        if ($idEmpresa) {
            $sql .= " AND idEmpresa = ?";
            $params[] = $idEmpresa;
        }
        return $this->db->query($sql, $params)->row()->total;
    }

    public function getDespesaConsolidada($dataInicio, $dataFim, $idEmpresa = null)
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) as total FROM contas_pagar WHERE vencimento BETWEEN ? AND ?";
        $params = [$dataInicio, $dataFim];
        if ($idEmpresa) {
            $sql .= " AND idEmpresa = ?";
            $params[] = $idEmpresa;
        }
        return $this->db->query($sql, $params)->row()->total;
    }

    public function getEvolucaoMensal($meses = 12, $idEmpresa = null)
    {
        $whereExtra = '';
        $params = [$meses - 1, $meses - 1];
        if ($idEmpresa) {
            $whereExtra = " AND idEmpresa = ?";
            $params = [$meses - 1, $idEmpresa, $meses - 1, $idEmpresa];
        }

        $result = $this->db->query("
            SELECT mes, SUM(receita) as receita, SUM(despesa) as despesa
            FROM (
                SELECT DATE_FORMAT(vencimento, '%Y-%m') as mes,
                       SUM(valor) as receita,
                       0 as despesa
                FROM contas_receber
                WHERE vencimento >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL ? MONTH), '%Y-%m-01')
                $whereExtra
                GROUP BY DATE_FORMAT(vencimento, '%Y-%m')
                UNION ALL
                SELECT DATE_FORMAT(vencimento, '%Y-%m') as mes,
                       0 as receita,
                       SUM(valor) as despesa
                FROM contas_pagar
                WHERE vencimento >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL ? MONTH), '%Y-%m-01')
                $whereExtra
                GROUP BY DATE_FORMAT(vencimento, '%Y-%m')
            ) m
            GROUP BY mes
            ORDER BY mes ASC
        ", $params)->result();

        $data = [];
        for ($i = $meses - 1; $i >= 0; $i--) {
            $mes = date('Y-m', strtotime("-$i months"));
            $data[$mes] = ['receita' => 0, 'despesa' => 0];
        }

        foreach ($result as $r) {
            if (isset($data[$r->mes])) {
                $data[$r->mes] = [
                    'receita' => (float)$r->receita,
                    'despesa' => (float)$r->despesa,
                ];
            }
        }

        $labels = [];
        $receitas = [];
        $despesas = [];
        $lucros = [];
        foreach ($data as $mes => $val) {
            $parts = explode('-', $mes);
            $labels[] = $this->mesAbreviado((int)$parts[1]) . '/' . substr($parts[0], 2);
            $receitas[] = $val['receita'];
            $despesas[] = $val['despesa'];
            $lucros[] = $val['receita'] - $val['despesa'];
        }

        return (object)[
            'labels' => $labels,
            'receitas' => $receitas,
            'despesas' => $despesas,
            'lucros' => $lucros,
        ];
    }

    public function getReceitaDespesaPorEmpresa($dataInicio, $dataFim, $idEmpresa = null)
    {
        $sql = "
            SELECT
                e.idEmpresa,
                e.nomeEmpresa,
                (SELECT COALESCE(SUM(valor), 0) FROM contas_receber WHERE idEmpresa = e.idEmpresa AND vencimento BETWEEN ? AND ?) as receita,
                (SELECT COALESCE(SUM(valor), 0) FROM contas_pagar WHERE idEmpresa = e.idEmpresa AND vencimento BETWEEN ? AND ?) as despesa
            FROM empresa e
            WHERE e.status = 1
        ";
        $params = [$dataInicio, $dataFim, $dataInicio, $dataFim];
        if ($idEmpresa) {
            $sql .= " AND e.idEmpresa = ?";
            $params[] = $idEmpresa;
        }
        $sql .= " ORDER BY receita DESC";
        return $this->db->query($sql, $params)->result();
    }

    public function getMixReceitaPorEmpresa($dataInicio, $dataFim, $idEmpresa = null)
    {
        $sql = "
            SELECT
                e.idEmpresa,
                e.nomeEmpresa,
                (SELECT COALESCE(SUM(valor), 0) FROM contas_receber WHERE idEmpresa = e.idEmpresa AND vencimento BETWEEN ? AND ?) as receita
            FROM empresa e
            WHERE e.status = 1
        ";
        $params = [$dataInicio, $dataFim];
        if ($idEmpresa) {
            $sql .= " AND e.idEmpresa = ?";
            $params[] = $idEmpresa;
        }
        $sql .= " HAVING receita > 0 ORDER BY receita DESC";
        return $this->db->query($sql, $params)->result();
    }

    public function getIndicadoresPorEmpresa($dataInicio, $dataFim, $idEmpresa = null)
    {
        $sql = "
            SELECT
                e.idEmpresa,
                e.nomeEmpresa,
                (SELECT COUNT(*) FROM unidade WHERE idEmpresa = e.idEmpresa AND status = 1) as qtdUnidades,
                (SELECT COALESCE(SUM(valor), 0) FROM contas_receber WHERE idEmpresa = e.idEmpresa AND vencimento BETWEEN ? AND ?) as receita,
                (SELECT COALESCE(SUM(valor), 0) FROM contas_pagar WHERE idEmpresa = e.idEmpresa AND vencimento BETWEEN ? AND ?) as despesa
            FROM empresa e
            WHERE e.status = 1
        ";
        $params = [$dataInicio, $dataFim, $dataInicio, $dataFim];
        if ($idEmpresa) {
            $sql .= " AND e.idEmpresa = ?";
            $params[] = $idEmpresa;
        }
        $sql .= " ORDER BY receita DESC";
        return $this->db->query($sql, $params)->result();
    }

    public function getTopUnidadesPorLucro($dataInicio, $dataFim, $limit = 5, $idEmpresa = null)
    {
        $sql = "
            SELECT * FROM (
                SELECT
                    u.idUnidade,
                    u.nomeUnidade,
                    e.nomeEmpresa,
                    (SELECT COALESCE(SUM(valor), 0) FROM contas_receber WHERE idUnidade = u.idUnidade AND vencimento BETWEEN ? AND ?) as receita,
                    (SELECT COALESCE(SUM(valor), 0) FROM contas_pagar WHERE idUnidade = u.idUnidade AND vencimento BETWEEN ? AND ?) as despesa
                FROM unidade u
                JOIN empresa e ON e.idEmpresa = u.idEmpresa
                WHERE u.status = 1
        ";
        $params = [$dataInicio, $dataFim, $dataInicio, $dataFim];
        if ($idEmpresa) {
            $sql .= " AND e.idEmpresa = ?";
            $params[] = $idEmpresa;
        }
        $sql .= "
            ) sub
            ORDER BY (receita - despesa) DESC
            LIMIT ?
        ";
        $params[] = $limit;
        return $this->db->query($sql, $params)->result();
    }

    private function mesAbreviado($num)
    {
        $meses = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        return $meses[(int)$num] ?? '';
    }
}
