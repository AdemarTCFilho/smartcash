<?php
class BaixasFinanceiras_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function aplicaFiltroPeriodo($tipo, $periodo)
    {
        if (!$periodo) return;
        switch ($tipo) {
            case 'mensal':
                $this->db->like('cr.vencimento', $periodo . '-', 'after');
                break;
            case 'trimestral':
                preg_match('/T(\d)\/(\d{4})/', $periodo, $m);
                if (count($m) === 3) {
                    $t = (int)$m[1]; $ano = $m[2];
                    $start = $ano . '-' . str_pad(($t-1)*3+1, 2, '0', STR_PAD_LEFT) . '-01';
                    $endMonth = ($t-1)*3+3;
                    $end = $ano . '-' . str_pad($endMonth, 2, '0', STR_PAD_LEFT) . '-' . date('t', strtotime($ano . '-' . str_pad($endMonth, 2, '0') . '-01'));
                    $this->db->where('cr.vencimento >=', $start);
                    $this->db->where('cr.vencimento <=', $end);
                }
                break;
            case 'anual':
                $this->db->where('YEAR(cr.vencimento)', (int)$periodo);
                break;
        }
    }

    private function aplicaFiltroPeriodoPagar($tipo, $periodo)
    {
        if (!$periodo) return;
        switch ($tipo) {
            case 'mensal':
                $this->db->like('cp.vencimento', $periodo . '-', 'after');
                break;
            case 'trimestral':
                preg_match('/T(\d)\/(\d{4})/', $periodo, $m);
                if (count($m) === 3) {
                    $t = (int)$m[1]; $ano = $m[2];
                    $start = $ano . '-' . str_pad(($t-1)*3+1, 2, '0', STR_PAD_LEFT) . '-01';
                    $endMonth = ($t-1)*3+3;
                    $end = $ano . '-' . str_pad($endMonth, 2, '0', STR_PAD_LEFT) . '-' . date('t', strtotime($ano . '-' . str_pad($endMonth, 2, '0') . '-01'));
                    $this->db->where('cp.vencimento >=', $start);
                    $this->db->where('cp.vencimento <=', $end);
                }
                break;
            case 'anual':
                $this->db->where('YEAR(cp.vencimento)', (int)$periodo);
                break;
        }
    }

    public function getResumo($tipoPeriodo = null, $periodo = null, $idEmpresa = null)
    {
        $this->db->select('COALESCE(SUM(valor - IFNULL(valorPago,0)), 0) as saldoDevedor, COUNT(*) as totalTitulos');
        $this->db->from('contas_pagar cp');
        $this->db->where('cp.status', 'pendente');
        if ($idEmpresa) $this->db->where('cp.idEmpresa', $idEmpresa);
        $this->aplicaFiltroPeriodoPagar($tipoPeriodo, $periodo);
        $aPagar = $this->db->get()->row();

        $this->db->reset_query();
        $this->db->select('COALESCE(SUM(valor - IFNULL(valorRecebido,0)), 0) as saldoReceber, COUNT(*) as totalTitulos');
        $this->db->from('contas_receber cr');
        $this->db->where('cr.status', 'pendente');
        if ($idEmpresa) $this->db->where('cr.idEmpresa', $idEmpresa);
        $this->aplicaFiltroPeriodo($tipoPeriodo, $periodo);
        $aReceber = $this->db->get()->row();

        return [
            'saldoDevedor' => (float)$aPagar->saldoDevedor,
            'totalAPagar' => (int)$aPagar->totalTitulos,
            'saldoReceber' => (float)$aReceber->saldoReceber,
            'totalAReceber' => (int)$aReceber->totalTitulos,
        ];
    }

    public function getContasPagarPendentes($tipoPeriodo = null, $periodo = null, $idEmpresa = null, $busca = null)
    {
        $this->db->select('cp.*, cl.nomeCliente as fornecedor, e.nomeEmpresa, u.nomeUnidade');
        $this->db->from('contas_pagar cp');
        $this->db->join('clientes cl', 'cl.idClientes = cp.idClientes', 'left');
        $this->db->join('empresa e', 'e.idEmpresa = cp.idEmpresa', 'left');
        $this->db->join('unidade u', 'u.idUnidade = cp.idUnidade', 'left');
        $this->db->where('cp.status', 'pendente');
        if ($idEmpresa) $this->db->where('cp.idEmpresa', $idEmpresa);
        if ($busca) {
            $this->db->group_start();
            $this->db->like('cl.nomeCliente', $busca);
            $this->db->or_like('cl.documento', $busca);
            $this->db->group_end();
        }
        $this->aplicaFiltroPeriodoPagar($tipoPeriodo, $periodo);
        $this->db->order_by('cp.vencimento', 'asc');
        return $this->db->get()->result();
    }

    public function getContasReceberPendentes($tipoPeriodo = null, $periodo = null, $idEmpresa = null, $busca = null)
    {
        $this->db->select('cr.*, cl.nomeCliente, e.nomeEmpresa, u.nomeUnidade');
        $this->db->from('contas_receber cr');
        $this->db->join('clientes cl', 'cl.idClientes = cr.idClientes', 'left');
        $this->db->join('empresa e', 'e.idEmpresa = cr.idEmpresa', 'left');
        $this->db->join('unidade u', 'u.idUnidade = cr.idUnidade', 'left');
        $this->db->where('cr.status', 'pendente');
        if ($idEmpresa) $this->db->where('cr.idEmpresa', $idEmpresa);
        if ($busca) {
            $this->db->group_start();
            $this->db->like('cl.nomeCliente', $busca);
            $this->db->or_like('cl.documento', $busca);
            $this->db->group_end();
        }
        $this->aplicaFiltroPeriodo($tipoPeriodo, $periodo);
        $this->db->order_by('cr.vencimento', 'asc');
        return $this->db->get()->result();
    }

    public function getHistoricoPagamentos($tipoPeriodo = null, $periodo = null, $idEmpresa = null)
    {
        $this->db->select('cp.*, cl.nomeCliente as fornecedor, e.nomeEmpresa');
        $this->db->from('contas_pagar cp');
        $this->db->join('clientes cl', 'cl.idClientes = cp.idClientes', 'left');
        $this->db->join('empresa e', 'e.idEmpresa = cp.idEmpresa', 'left');
        $this->db->where('cp.status', 'liquidado');
        if ($idEmpresa) $this->db->where('cp.idEmpresa', $idEmpresa);
        $this->aplicaFiltroPeriodoPagar($tipoPeriodo, $periodo);
        $this->db->order_by('cp.dataPagamento', 'desc');
        return $this->db->get()->result();
    }

    public function getHistoricoRecebimentos($tipoPeriodo = null, $periodo = null, $idEmpresa = null)
    {
        $this->db->select('cr.*, cl.nomeCliente, e.nomeEmpresa');
        $this->db->from('contas_receber cr');
        $this->db->join('clientes cl', 'cl.idClientes = cr.idClientes', 'left');
        $this->db->join('empresa e', 'e.idEmpresa = cr.idEmpresa', 'left');
        $this->db->where('cr.status', 'liquidado');
        if ($idEmpresa) $this->db->where('cr.idEmpresa', $idEmpresa);
        $this->aplicaFiltroPeriodo($tipoPeriodo, $periodo);
        $this->db->order_by('cr.dataRecebimento', 'desc');
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

    public function getUnidadesPorEmpresa($idEmpresa)
    {
        $this->db->select('*');
        $this->db->from('unidade');
        $this->db->where('idEmpresa', $idEmpresa);
        $this->db->where('status', 1);
        $this->db->order_by('nomeUnidade', 'asc');
        return $this->db->get()->result();
    }
}
