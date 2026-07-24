<?php
class DreGerencial_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getTotalReceitas($mes, $idUnidade = null, $idEmpresa = null)
    {
        $this->db->select('COALESCE(SUM(valor), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_receber');
        $this->db->where("DATE_FORMAT(vencimento, '%Y-%m') = ", $mes);
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        if ($idEmpresa) $this->db->where('idEmpresa', $idEmpresa);
        return $this->db->get()->row();
    }

    public function getTotalDespesas($mes, $idUnidade = null, $idEmpresa = null)
    {
        $this->db->select('COALESCE(SUM(valor), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_pagar');
        $this->db->where("DATE_FORMAT(vencimento, '%Y-%m') = ", $mes);
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        if ($idEmpresa) $this->db->where('idEmpresa', $idEmpresa);
        return $this->db->get()->row();
    }

    public function getReceitasPorCategoria($mes, $idUnidade = null, $idEmpresa = null)
    {
        $this->db->select('c.idCategoria, c.nomeCategoria, COALESCE(SUM(cr.valor), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_receber cr');
        $this->db->join('categoria c', 'c.idCategoria = cr.idCategoria');
        $this->db->where("DATE_FORMAT(cr.vencimento, '%Y-%m') = ", $mes);
        if ($idUnidade) $this->db->where('cr.idUnidade', $idUnidade);
        if ($idEmpresa) $this->db->where('cr.idEmpresa', $idEmpresa);
        $this->db->group_by('c.idCategoria, c.nomeCategoria');
        $this->db->order_by('total', 'desc');
        return $this->db->get()->result();
    }

    public function getDespesasPorCategoria($mes, $idUnidade = null, $idEmpresa = null)
    {
        $this->db->select('c.idCategoria, c.nomeCategoria, COALESCE(SUM(cp.valor), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_pagar cp');
        $this->db->join('categoria c', 'c.idCategoria = cp.idCategoria');
        $this->db->where("DATE_FORMAT(cp.vencimento, '%Y-%m') = ", $mes);
        if ($idUnidade) $this->db->where('cp.idUnidade', $idUnidade);
        if ($idEmpresa) $this->db->where('cp.idEmpresa', $idEmpresa);
        $this->db->group_by('c.idCategoria, c.nomeCategoria');
        $this->db->order_by('total', 'desc');
        return $this->db->get()->result();
    }

    public function getComparativo($mes, $idUnidade = null, $idEmpresa = null)
    {
        $dt = new DateTime($mes . '-01');
        $mesAtual = $dt->format('Y-m');
        $dt->modify('-1 month');
        $mesAnterior = $dt->format('Y-m');

        $recAtual = $this->getTotalReceitas($mesAtual, $idUnidade, $idEmpresa);
        $despAtual = $this->getTotalDespesas($mesAtual, $idUnidade, $idEmpresa);
        $recAnt = $this->getTotalReceitas($mesAnterior, $idUnidade, $idEmpresa);
        $despAnt = $this->getTotalDespesas($mesAnterior, $idUnidade, $idEmpresa);

        return [
            'mesAtual' => $mesAtual,
            'mesAnterior' => $mesAnterior,
            'receitas' => [
                'atual' => (float)$recAtual->total,
                'anterior' => (float)$recAnt->total,
            ],
            'despesas' => [
                'atual' => (float)$despAtual->total,
                'anterior' => (float)$despAnt->total,
            ],
            'resultado' => [
                'atual' => (float)$recAtual->total - (float)$despAtual->total,
                'anterior' => (float)$recAnt->total - (float)$despAnt->total,
            ],
        ];
    }

    public function getEvolucao($mesFim, $idUnidade = null, $idEmpresa = null)
    {
        $dt = new DateTime($mesFim . '-01');
        $dados = [];
        for ($i = 0; $i < 12; $i++) {
            $mes = $dt->format('Y-m');
            $rec = $this->getTotalReceitas($mes, $idUnidade, $idEmpresa);
            $desp = $this->getTotalDespesas($mes, $idUnidade, $idEmpresa);
            $dados[] = [
                'mes' => $mes,
                'receitas' => (float)$rec->total,
                'despesas' => (float)$desp->total,
                'resultado' => (float)$rec->total - (float)$desp->total,
            ];
            $dt->modify('-1 month');
        }
        return array_reverse($dados);
    }

    public function getAllEmpresas()
    {
        return $this->db->query("SELECT idEmpresa, nomeEmpresa FROM empresa WHERE status = 1 ORDER BY nomeEmpresa ASC")->result();
    }

    public function getUnidadesPorEmpresa($idEmpresa)
    {
        if ($idEmpresa) {
            return $this->db->query("SELECT idUnidade, nomeUnidade FROM unidade WHERE idEmpresa = ? AND status = 1 ORDER BY nomeUnidade ASC", [$idEmpresa])->result();
        }
        return $this->db->query("SELECT idUnidade, nomeUnidade, idEmpresa FROM unidade WHERE status = 1 ORDER BY nomeEmpresa, nomeUnidade ASC")->result();
    }
}
