<?php
class ContasBancarias_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $this->db->select('
            cb.*,
            e.nomeEmpresa,
            u.nome AS nomeUsuario,
            un.nomeUnidade,
            sn.nomeSubUnidade
        ');
        $this->db->from('contas_bancarias cb');
        $this->db->join('empresa e', 'e.idEmpresa = cb.idEmpresa', 'left');
        $this->db->join('usuarios u', 'u.idUsuarios = cb.idUsuarios', 'left');
        $this->db->join('unidade un', 'un.idUnidade = cb.idUnidade', 'left');
        $this->db->join('sub_unidade sn', 'sn.idSubUnidade = cb.idSubUnidade', 'left');
        $this->db->order_by('cb.nome', 'asc');
        return $this->db->get()->result();
    }

    public function getById($id)
    {
        $this->db->select('*');
        $this->db->from('contas_bancarias');
        $this->db->where('idContaBancaria', $id);
        return $this->db->get()->row();
    }

    public function add($data)
    {
        return $this->db->insert('contas_bancarias', $data);
    }

    public function edit($id, $data)
    {
        $this->db->where('idContaBancaria', $id);
        return $this->db->update('contas_bancarias', $data);
    }

    public function delete($id)
    {
        $this->db->where('idContaBancaria', $id);
        return $this->db->delete('contas_bancarias');
    }

    public function getSaldoConsolidado()
    {
        $this->db->select('COALESCE(SUM(saldoInicial), 0) as total');
        $this->db->from('contas_bancarias');
        return $this->db->get()->row()->total;
    }

    public function getAllEmpresas()
    {
        $this->db->select('idEmpresa, nomeEmpresa');
        $this->db->from('empresa');
        $this->db->where('status', 1);
        $this->db->order_by('nomeEmpresa', 'asc');
        return $this->db->get()->result();
    }

    public function getAllUsuarios()
    {
        $this->db->select('idUsuarios, nome');
        $this->db->from('usuarios');
        $this->db->where('situacao', 1);
        $this->db->order_by('nome', 'asc');
        return $this->db->get()->result();
    }

    public function getAllUnidades()
    {
        $this->db->select('idUnidade, nomeUnidade');
        $this->db->from('unidade');
        $this->db->where('status', 1);
        $this->db->order_by('nomeUnidade', 'asc');
        return $this->db->get()->result();
    }

    public function getUnidadesPorEmpresa($idEmpresa)
    {
        $this->db->select('idUnidade, nomeUnidade');
        $this->db->from('unidade');
        $this->db->where('idEmpresa', $idEmpresa);
        $this->db->where('status', 1);
        $this->db->order_by('nomeUnidade', 'asc');
        return $this->db->get()->result();
    }

    public function getSubUnidadesPorUnidade($idUnidade)
    {
        $this->db->select('idSubUnidade, nomeSubUnidade');
        $this->db->from('sub_unidade');
        $this->db->where('status', 1);
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        $this->db->order_by('nomeSubUnidade', 'asc');
        return $this->db->get()->result();
    }
}
