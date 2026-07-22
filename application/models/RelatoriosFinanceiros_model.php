<?php
class RelatoriosFinanceiros_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function applyUnidadeFilter($idUnidade)
    {
        if (!empty($idUnidade)) {
            $this->db->where('idUnidade', $idUnidade);
        }
    }

    public function getTotalRecebido($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $this->db->select('COALESCE(SUM(valorRecebido), 0) as total, COUNT(*) as totalContas');
        $this->db->from('contas_receber');
        $this->db->where('status', 'liquidado');
        if ($dataInicio) $this->db->where('dataRecebimento >=', $dataInicio);
        if ($dataFim) $this->db->where('dataRecebimento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        return $this->db->get()->row();
    }

    public function getTotalPago($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $this->db->select('COALESCE(SUM(valorPago), 0) as total, COUNT(*) as totalContas');
        $this->db->from('contas_pagar');
        $this->db->where('status', 'liquidado');
        if ($dataInicio) $this->db->where('dataPagamento >=', $dataInicio);
        if ($dataFim) $this->db->where('dataPagamento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        return $this->db->get()->row();
    }

    public function getTotalAReceber($idUnidade = null)
    {
        $this->db->select('COALESCE(SUM(valor - valorRecebido), 0) as total, COUNT(*) as totalContas');
        $this->db->from('contas_receber');
        $this->db->where('status', 'pendente');
        $this->applyUnidadeFilter($idUnidade);
        return $this->db->get()->row();
    }

    public function getTotalAPagar($idUnidade = null)
    {
        $this->db->select('COALESCE(SUM(valor - valorPago), 0) as total, COUNT(*) as totalContas');
        $this->db->from('contas_pagar');
        $this->db->where('status', 'pendente');
        $this->applyUnidadeFilter($idUnidade);
        return $this->db->get()->row();
    }

    public function getInadimplencia($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $this->db->select('COALESCE(SUM(valor - valorRecebido), 0) as total, COUNT(*) as totalContas');
        $this->db->from('contas_receber');
        $this->db->where('status', 'pendente');
        $this->db->where('vencimento <', date('Y-m-d'));
        if ($dataInicio) $this->db->where('vencimento >=', $dataInicio);
        if ($dataFim) $this->db->where('vencimento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        return $this->db->get()->row();
    }

    public function getPagarVencidas($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $this->db->select('COALESCE(SUM(valor - valorPago), 0) as total, COUNT(*) as totalContas');
        $this->db->from('contas_pagar');
        $this->db->where('status', 'pendente');
        $this->db->where('vencimento <', date('Y-m-d'));
        if ($dataInicio) $this->db->where('vencimento >=', $dataInicio);
        if ($dataFim) $this->db->where('vencimento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        return $this->db->get()->row();
    }

    public function getListaInadimplencia($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $this->db->select('
            contas_receber.*,
            clientes.nomeCliente
        ');
        $this->db->from('contas_receber');
        $this->db->join('clientes', 'clientes.idClientes = contas_receber.idClientes', 'left');
        $this->db->where('contas_receber.status', 'pendente');
        $this->db->where('contas_receber.vencimento <', date('Y-m-d'));
        if ($dataInicio) $this->db->where('contas_receber.vencimento >=', $dataInicio);
        if ($dataFim) $this->db->where('contas_receber.vencimento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        $this->db->order_by('contas_receber.vencimento', 'asc');
        return $this->db->get()->result();
    }

    public function getContasReceber($status = null, $dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $this->db->select('
            contas_receber.*,
            clientes.nomeCliente,
            usuarios.nome as nomeUsuario,
            empresa.nomeEmpresa,
            unidade.nomeUnidade,
            categoria.nomeCategoria
        ');
        $this->db->from('contas_receber');
        $this->db->join('clientes', 'clientes.idClientes = contas_receber.idClientes', 'left');
        $this->db->join('usuarios', 'usuarios.idUsuarios = contas_receber.idUsuarios', 'left');
        $this->db->join('empresa', 'empresa.idEmpresa = contas_receber.idEmpresa', 'left');
        $this->db->join('unidade', 'unidade.idUnidade = contas_receber.idUnidade', 'left');
        $this->db->join('categoria', 'categoria.idCategoria = contas_receber.idCategoria', 'left');
        if ($status) $this->db->where('contas_receber.status', $status);
        if ($dataInicio) $this->db->where('contas_receber.vencimento >=', $dataInicio);
        if ($dataFim) $this->db->where('contas_receber.vencimento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        $this->db->order_by('contas_receber.vencimento', 'asc');
        return $this->db->get()->result();
    }

    public function getContasPagar($status = null, $dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $this->db->select('
            contas_pagar.*,
            clientes.nomeCliente,
            usuarios.nome as nomeUsuario,
            empresa.nomeEmpresa,
            unidade.nomeUnidade,
            categoria.nomeCategoria
        ');
        $this->db->from('contas_pagar');
        $this->db->join('clientes', 'clientes.idClientes = contas_pagar.idClientes', 'left');
        $this->db->join('usuarios', 'usuarios.idUsuarios = contas_pagar.idUsuarios', 'left');
        $this->db->join('empresa', 'empresa.idEmpresa = contas_pagar.idEmpresa', 'left');
        $this->db->join('unidade', 'unidade.idUnidade = contas_pagar.idUnidade', 'left');
        $this->db->join('categoria', 'categoria.idCategoria = contas_pagar.idCategoria', 'left');
        if ($status) $this->db->where('contas_pagar.status', $status);
        if ($dataInicio) $this->db->where('contas_pagar.vencimento >=', $dataInicio);
        if ($dataFim) $this->db->where('contas_pagar.vencimento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        $this->db->order_by('contas_pagar.vencimento', 'asc');
        return $this->db->get()->result();
    }

    public function getHistoricoPagamentos($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        return $this->getContasPagar('liquidado', $dataInicio, $dataFim, $idUnidade);
    }

    public function getHistoricoRecebimentos($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        return $this->getContasReceber('liquidado', $dataInicio, $dataFim, $idUnidade);
    }

    public function getAllUnidades()
    {
        $this->db->select('idUnidade, nomeUnidade');
        $this->db->from('unidade');
        $this->db->where('status', 1);
        $this->db->order_by('nomeUnidade', 'asc');
        return $this->db->get()->result();
    }

    public function getReceitasPorCategoria($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $this->db->select('
            COALESCE(categoria.nomeCategoria, "Sem categoria") as nomeCategoria,
            COALESCE(SUM(contas_receber.valorRecebido), 0) as total
        ');
        $this->db->from('contas_receber');
        $this->db->join('categoria', 'categoria.idCategoria = contas_receber.idCategoria', 'left');
        $this->db->where('contas_receber.status', 'liquidado');
        if ($dataInicio) $this->db->where('contas_receber.dataRecebimento >=', $dataInicio);
        if ($dataFim) $this->db->where('contas_receber.dataRecebimento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        $this->db->group_by('contas_receber.idCategoria');
        $this->db->order_by('total', 'desc');
        return $this->db->get()->result();
    }

    public function getDespesasPorCategoria($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $this->db->select('
            COALESCE(categoria.nomeCategoria, "Sem categoria") as nomeCategoria,
            COALESCE(SUM(contas_pagar.valorPago), 0) as total
        ');
        $this->db->from('contas_pagar');
        $this->db->join('categoria', 'categoria.idCategoria = contas_pagar.idCategoria', 'left');
        $this->db->where('contas_pagar.status', 'liquidado');
        if ($dataInicio) $this->db->where('contas_pagar.dataPagamento >=', $dataInicio);
        if ($dataFim) $this->db->where('contas_pagar.dataPagamento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        $this->db->group_by('contas_pagar.idCategoria');
        $this->db->order_by('total', 'desc');
        return $this->db->get()->result();
    }

    public function getPorUnidade($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $crJoin = 'contas_receber.idUnidade = unidade.idUnidade AND contas_receber.status = "liquidado"';
        $cpJoin = 'contas_pagar.idUnidade = unidade.idUnidade AND contas_pagar.status = "liquidado"';
        if ($dataInicio) {
            $crJoin .= ' AND contas_receber.dataRecebimento >= ' . $this->db->escape($dataInicio);
            $cpJoin .= ' AND contas_pagar.dataPagamento >= ' . $this->db->escape($dataInicio);
        }
        if ($dataFim) {
            $crJoin .= ' AND contas_receber.dataRecebimento <= ' . $this->db->escape($dataFim);
            $cpJoin .= ' AND contas_pagar.dataPagamento <= ' . $this->db->escape($dataFim);
        }

        $this->db->select('
            unidade.nomeUnidade,
            COALESCE(SUM(contas_receber.valorRecebido), 0) as receita,
            COALESCE(SUM(contas_pagar.valorPago), 0) as despesa
        ');
        $this->db->from('unidade');
        $this->db->join('contas_receber', $crJoin, 'left');
        $this->db->join('contas_pagar', $cpJoin, 'left');
        $this->db->where('unidade.status', 1);
        if ($idUnidade) $this->db->where('unidade.idUnidade', $idUnidade);
        $this->db->group_by('unidade.idUnidade');
        $this->db->order_by('unidade.nomeUnidade', 'asc');
        return $this->db->get()->result();
    }

    public function getTopClientes($dataInicio = null, $dataFim = null, $idUnidade = null, $limit = 10)
    {
        $this->db->select('
            clientes.nomeCliente,
            COALESCE(SUM(contas_receber.valorRecebido), 0) as total
        ');
        $this->db->from('contas_receber');
        $this->db->join('clientes', 'clientes.idClientes = contas_receber.idClientes', 'left');
        $this->db->where('contas_receber.status', 'liquidado');
        if ($dataInicio) $this->db->where('contas_receber.dataRecebimento >=', $dataInicio);
        if ($dataFim) $this->db->where('contas_receber.dataRecebimento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        $this->db->group_by('contas_receber.idClientes');
        $this->db->order_by('total', 'desc');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function getTopFornecedores($dataInicio = null, $dataFim = null, $idUnidade = null, $limit = 10)
    {
        $this->db->select('
            clientes.nomeCliente,
            COALESCE(SUM(contas_pagar.valorPago), 0) as total
        ');
        $this->db->from('contas_pagar');
        $this->db->join('clientes', 'clientes.idClientes = contas_pagar.idClientes', 'left');
        $this->db->where('contas_pagar.status', 'liquidado');
        if ($dataInicio) $this->db->where('contas_pagar.dataPagamento >=', $dataInicio);
        if ($dataFim) $this->db->where('contas_pagar.dataPagamento <=', $dataFim);
        $this->applyUnidadeFilter($idUnidade);
        $this->db->group_by('contas_pagar.idClientes');
        $this->db->order_by('total', 'desc');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function getPorFormaPagamento($dataInicio = null, $dataFim = null, $idUnidade = null)
    {
        $this->db->select('
            "Dinheiro" as forma,
            COALESCE(SUM(CASE WHEN contas_receber.status = "liquidado" THEN contas_receber.valorRecebido ELSE 0 END), 0) as entradas,
            COALESCE(SUM(CASE WHEN contas_pagar.status = "liquidado" THEN contas_pagar.valorPago ELSE 0 END), 0) as saidas
        ');
        $this->db->from('contas_receber');
        $this->db->join('contas_pagar', '1=1', 'cross');
        $this->db->where('contas_receber.status', 'liquidado');
        $this->db->where('contas_pagar.status', 'liquidado');
        return $this->db->get()->result();
    }
}
