<?php
class RankingReceitasDespesas_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function applyPeriodFilter($tipoPeriodo, $periodo)
    {
        if (!$periodo) return;

        switch ($tipoPeriodo) {
            case 'mensal':
                $this->db->like('vencimento', $periodo . '-', 'after');
                break;
            case 'trimestral':
                preg_match('/T(\d)\/(\d{4})/', $periodo, $matches);
                if (count($matches) === 3) {
                    $t = (int)$matches[1];
                    $ano = $matches[2];
                    $startMonth = ($t - 1) * 3 + 1;
                    $start = $ano . '-' . str_pad($startMonth, 2, '0', STR_PAD_LEFT) . '-01';
                    $endMonth = $startMonth + 2;
                    $lastDay = date('t', strtotime($ano . '-' . str_pad($endMonth, 2, '0', STR_PAD_LEFT) . '-01'));
                    $end = $ano . '-' . str_pad($endMonth, 2, '0', STR_PAD_LEFT) . '-' . $lastDay;
                    $this->db->where('vencimento >=', $start);
                    $this->db->where('vencimento <=', $end);
                }
                break;
            case 'anual':
                $this->db->where('YEAR(vencimento)', (int)$periodo);
                break;
        }
    }

    private function applyEmpresaFilter($idEmpresa)
    {
        if ($idEmpresa) {
            $this->db->where('idEmpresa', $idEmpresa);
        }
    }

    public function getResumo($tipoPeriodo, $periodo, $idEmpresa = null)
    {
        $this->db->select('COALESCE(SUM(valor), 0) as total, COUNT(*) as totalContas');
        $this->db->from('contas_receber');
        $this->applyEmpresaFilter($idEmpresa);
        $this->applyPeriodFilter($tipoPeriodo, $periodo);
        $receitas = $this->db->get()->row();

        $this->db->reset_query();
        $this->db->select('COALESCE(SUM(valor), 0) as total, COUNT(*) as totalContas');
        $this->db->from('contas_pagar');
        $this->applyEmpresaFilter($idEmpresa);
        $this->applyPeriodFilter($tipoPeriodo, $periodo);
        $despesas = $this->db->get()->row();

        return [
            'totalReceitas' => (float)$receitas->total,
            'totalDespesas' => (float)$despesas->total,
            'totalContasReceber' => (int)$receitas->totalContas,
            'totalContasPagar' => (int)$despesas->totalContas,
            'saldo' => (float)$receitas->total - (float)$despesas->total,
        ];
    }

    public function getPorEmpresa($tipoPeriodo, $periodo, $idEmpresa = null)
    {
        $sqlReceita = "
            SELECT e.idEmpresa, e.nomeEmpresa,
                   COALESCE(SUM(r.valor), 0) as receita,
                   0 as despesa
            FROM empresa e
            LEFT JOIN contas_receber r ON r.idEmpresa = e.idEmpresa
        ";
        $sqlDespesa = "
            SELECT e.idEmpresa, e.nomeEmpresa,
                   0 as receita,
                   COALESCE(SUM(p.valor), 0) as despesa
            FROM empresa e
            LEFT JOIN contas_pagar p ON p.idEmpresa = e.idEmpresa
        ";

        $where = $this->buildPeriodWhere($tipoPeriodo, $periodo);

        if ($idEmpresa) {
            $where .= ($where ? ' AND ' : '') . 'e.idEmpresa = ' . (int)$idEmpresa;
        }

        $sqlReceita .= ($where ? ' WHERE ' . $where : '') . ' GROUP BY e.idEmpresa, e.nomeEmpresa';
        $sqlDespesa .= ($where ? ' WHERE ' . $where : '') . ' GROUP BY e.idEmpresa, e.nomeEmpresa';

        $receitas = $this->db->query($sqlReceita)->result_array();
        $despesas = $this->db->query($sqlDespesa)->result_array();

        $merged = [];
        foreach ($receitas as $r) {
            $merged[$r['idEmpresa']] = [
                'idEmpresa' => $r['idEmpresa'],
                'nomeEmpresa' => $r['nomeEmpresa'],
                'receita' => (float)$r['receita'],
                'despesa' => 0,
            ];
        }
        foreach ($despesas as $d) {
            if (!isset($merged[$d['idEmpresa']])) {
                $merged[$d['idEmpresa']] = [
                    'idEmpresa' => $d['idEmpresa'],
                    'nomeEmpresa' => $d['nomeEmpresa'],
                    'receita' => 0,
                    'despesa' => 0,
                ];
            }
            $merged[$d['idEmpresa']]['despesa'] = (float)$d['despesa'];
        }

        foreach ($merged as &$m) {
            $m['saldo'] = $m['receita'] - $m['despesa'];
        }

        return array_values($merged);
    }

    public function getPorUnidade($tipoPeriodo, $periodo, $idEmpresa = null)
    {
        $sqlReceita = "
            SELECT u.idUnidade, u.nomeUnidade, e.nomeEmpresa, e.idEmpresa,
                   COALESCE(SUM(r.valor), 0) as receita,
                   0 as despesa
            FROM unidade u
            JOIN empresa e ON e.idEmpresa = u.idEmpresa
            LEFT JOIN contas_receber r ON r.idUnidade = u.idUnidade
        ";
        $sqlDespesa = "
            SELECT u.idUnidade, u.nomeUnidade, e.nomeEmpresa, e.idEmpresa,
                   0 as receita,
                   COALESCE(SUM(p.valor), 0) as despesa
            FROM unidade u
            JOIN empresa e ON e.idEmpresa = u.idEmpresa
            LEFT JOIN contas_pagar p ON p.idUnidade = u.idUnidade
        ";

        $where = $this->buildPeriodWhere($tipoPeriodo, $periodo);
        if ($idEmpresa) {
            $where .= ($where ? ' AND ' : '') . 'e.idEmpresa = ' . (int)$idEmpresa;
        }

        $sqlReceita .= ($where ? ' WHERE ' . $where : '') . ' GROUP BY u.idUnidade, u.nomeUnidade, e.nomeEmpresa, e.idEmpresa';
        $sqlDespesa .= ($where ? ' WHERE ' . $where : '') . ' GROUP BY u.idUnidade, u.nomeUnidade, e.nomeEmpresa, e.idEmpresa';

        $receitas = $this->db->query($sqlReceita)->result_array();
        $despesas = $this->db->query($sqlDespesa)->result_array();

        $merged = [];
        foreach ($receitas as $r) {
            $merged[$r['idUnidade']] = [
                'idUnidade' => $r['idUnidade'],
                'nomeUnidade' => $r['nomeUnidade'],
                'idEmpresa' => $r['idEmpresa'],
                'nomeEmpresa' => $r['nomeEmpresa'],
                'receita' => (float)$r['receita'],
                'despesa' => 0,
            ];
        }
        foreach ($despesas as $d) {
            if (!isset($merged[$d['idUnidade']])) {
                $merged[$d['idUnidade']] = [
                    'idUnidade' => $d['idUnidade'],
                    'nomeUnidade' => $d['nomeUnidade'],
                    'idEmpresa' => $d['idEmpresa'],
                    'nomeEmpresa' => $d['nomeEmpresa'],
                    'receita' => 0,
                    'despesa' => 0,
                ];
            }
            $merged[$d['idUnidade']]['despesa'] = (float)$d['despesa'];
        }

        foreach ($merged as &$m) {
            $m['saldo'] = $m['receita'] - $m['despesa'];
        }

        return array_values($merged);
    }

    public function getRankingReceitas($tipoPeriodo, $periodo, $idEmpresa = null, $limit = 5)
    {
        $this->db->select('
            COALESCE(e.nomeEmpresa, "—") as nomeEmpresa,
            COALESCE(u.nomeUnidade, "—") as nomeUnidade,
            COALESCE(SUM(r.valor), 0) as total
        ');
        $this->db->from('contas_receber r');
        $this->db->join('empresa e', 'e.idEmpresa = r.idEmpresa', 'left');
        $this->db->join('unidade u', 'u.idUnidade = r.idUnidade', 'left');
        $this->applyEmpresaFilter($idEmpresa);
        $this->applyPeriodFilter($tipoPeriodo, $periodo);
        $this->db->group_by('r.idEmpresa, r.idUnidade');
        $this->db->order_by('total', 'desc');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function getRankingDespesas($tipoPeriodo, $periodo, $idEmpresa = null, $limit = 5)
    {
        $this->db->select('
            COALESCE(e.nomeEmpresa, "—") as nomeEmpresa,
            COALESCE(u.nomeUnidade, "—") as nomeUnidade,
            COALESCE(SUM(p.valor), 0) as total
        ');
        $this->db->from('contas_pagar p');
        $this->db->join('empresa e', 'e.idEmpresa = p.idEmpresa', 'left');
        $this->db->join('unidade u', 'u.idUnidade = p.idUnidade', 'left');
        $this->applyEmpresaFilter($idEmpresa);
        $this->applyPeriodFilter($tipoPeriodo, $periodo);
        $this->db->group_by('p.idEmpresa, p.idUnidade');
        $this->db->order_by('total', 'desc');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function getAllEmpresas()
    {
        $this->db->select('*');
        $this->db->from('empresa');
        $this->db->where('status', 1);
        $this->db->order_by('nomeEmpresa', 'asc');
        return $this->db->get()->result();
    }

    private function buildPeriodWhere($tipoPeriodo, $periodo)
    {
        if (!$periodo) return '';

        switch ($tipoPeriodo) {
            case 'mensal':
                return "vencimento LIKE '" . $this->db->escape_like_str($periodo) . "-%'";
            case 'trimestral':
                preg_match('/T(\d)\/(\d{4})/', $periodo, $matches);
                if (count($matches) !== 3) return '';
                $t = (int)$matches[1];
                $ano = $matches[2];
                $startMonth = ($t - 1) * 3 + 1;
                $start = $ano . '-' . str_pad($startMonth, 2, '0', STR_PAD_LEFT) . '-01';
                $endMonth = $startMonth + 2;
                $lastDay = date('t', strtotime($ano . '-' . str_pad($endMonth, 2, '0', STR_PAD_LEFT) . '-01'));
                $end = $ano . '-' . str_pad($endMonth, 2, '0', STR_PAD_LEFT) . '-' . $lastDay;
                return "vencimento >= '$start' AND vencimento <= '$end'";
            case 'anual':
                return 'YEAR(vencimento) = ' . (int)$periodo;
            default:
                return '';
        }
    }
}
