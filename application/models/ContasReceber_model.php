<?php
class ContasReceber_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }

        $query = $this->db->get();

        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return true;
        }

        return false;
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0) {
            return true;
        }

        return false;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1') {
            return true;
        }

        return false;
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function getAllContasReceber()
    {
        $this->db->select('
            contas_receber.*,
            clientes.nomeCliente,
            usuarios.nome as nomeUsuario,
            empresa.nomeEmpresa,
            unidade.nomeUnidade,
            sub_unidade.nomeSubUnidade,
            categoria.nomeCategoria
        ');
        $this->db->from('contas_receber');
        $this->db->join('clientes', 'clientes.idClientes = contas_receber.idClientes', 'left');
        $this->db->join('usuarios', 'usuarios.idUsuarios = contas_receber.idUsuarios', 'left');
        $this->db->join('empresa', 'empresa.idEmpresa = contas_receber.idEmpresa', 'left');
        $this->db->join('unidade', 'unidade.idUnidade = contas_receber.idUnidade', 'left');
        $this->db->join('sub_unidade', 'sub_unidade.idSubUnidade = contas_receber.idSubUnidade', 'left');
        $this->db->join('categoria', 'categoria.idCategoria = contas_receber.idCategoria', 'left');
        $this->db->order_by('contas_receber.dataCriacao', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getContaReceberById($id)
    {
        $this->db->select('
            contas_receber.*,
            clientes.nomeCliente,
            usuarios.nome as nomeUsuario,
            empresa.nomeEmpresa,
            unidade.nomeUnidade,
            sub_unidade.nomeSubUnidade,
            categoria.nomeCategoria
        ');
        $this->db->from('contas_receber');
        $this->db->join('clientes', 'clientes.idClientes = contas_receber.idClientes', 'left');
        $this->db->join('usuarios', 'usuarios.idUsuarios = contas_receber.idUsuarios', 'left');
        $this->db->join('empresa', 'empresa.idEmpresa = contas_receber.idEmpresa', 'left');
        $this->db->join('unidade', 'unidade.idUnidade = contas_receber.idUnidade', 'left');
        $this->db->join('sub_unidade', 'sub_unidade.idSubUnidade = contas_receber.idSubUnidade', 'left');
        $this->db->join('categoria', 'categoria.idCategoria = contas_receber.idCategoria', 'left');
        $this->db->where('idContaReceber', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getAllClientes()
    {
        $this->db->select('idClientes, nomeCliente, documento');
        $this->db->from('clientes');
        $this->db->order_by('nomeCliente', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllUsuarios()
    {
        $this->db->select('idUsuarios, nome');
        $this->db->from('usuarios');
        $this->db->order_by('nome', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllEmpresas()
    {
        $this->db->select('*');
        $this->db->from('empresa');
        $this->db->where('status', 1);
        $this->db->order_by('nomeEmpresa', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getUnidadesPorEmpresa($idEmpresa)
    {
        $this->db->select('*');
        $this->db->from('unidade');
        $this->db->where('idEmpresa', $idEmpresa);
        $this->db->where('status', 1);
        $this->db->order_by('nomeUnidade', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getSubUnidadesPorUnidade($idUnidade)
    {
        $this->db->select('*');
        $this->db->from('sub_unidade');
        $this->db->where('idUnidade', $idUnidade);
        $this->db->where('status', 1);
        $this->db->order_by('nomeSubUnidade', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllCategorias()
    {
        $this->db->select('*');
        $this->db->from('categoria');
        $this->db->where('status', 1);
        $this->db->order_by('nomeCategoria', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getTotalAReceber()
    {
        $this->db->select('COALESCE(SUM(valor), 0) as total, COUNT(*) as totalContas');
        $this->db->from('contas_receber');
        $query = $this->db->get();
        return $query->row();
    }

    public function getTotalRecebido()
    {
        $this->db->select('COALESCE(SUM(valor), 0) as total, COUNT(*) as totalContas');
        $this->db->from('contas_receber');
        $this->db->where('vencimento <', date('Y-m-d'));
        $query = $this->db->get();
        return $query->row();
    }

    public function getProximosVencimentos($limit = 5)
    {
        $this->db->select('
            contas_receber.*,
            clientes.nomeCliente
        ');
        $this->db->from('contas_receber');
        $this->db->join('clientes', 'clientes.idClientes = contas_receber.idClientes', 'left');
        $this->db->where('contas_receber.vencimento >=', date('Y-m-d'));
        $this->db->order_by('contas_receber.vencimento', 'asc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    public function getReceitasPorCategoria()
    {
        $this->db->select('
            COALESCE(categoria.nomeCategoria, "Sem categoria") as nomeCategoria,
            COALESCE(SUM(contas_receber.valor), 0) as total
        ');
        $this->db->from('contas_receber');
        $this->db->join('categoria', 'categoria.idCategoria = contas_receber.idCategoria', 'left');
        $this->db->group_by('contas_receber.idCategoria');
        $this->db->order_by('total', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
}
