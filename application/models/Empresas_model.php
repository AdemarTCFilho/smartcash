<?php
class Empresas_model extends CI_Model
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

    public function getActive($table, $fields)
    {
        $this->db->select($fields);
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result();
    }

    public function getById($id)
    {
        $this->db->limit(1);
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

    public function getAllEmpresas($where = '')
    {
        $this->db->select('*');
        $this->db->from('empresa');
        if ($where) {
            $this->db->where($where);
        }
        $this->db->order_by('nomeEmpresa', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllUnidades()
    {
        $this->db->select('unidade.*, empresa.nomeEmpresa, empresa.cnpjEmpresa');
        $this->db->from('unidade');
        $this->db->join('empresa', 'empresa.idEmpresa = unidade.idEmpresa', 'left');
        $this->db->order_by('unidade.nomeUnidade', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllSubUnidades()
    {
        $this->db->select('
            sub_unidade.*, 
            unidade.nomeUnidade, 
            empresa.nomeEmpresa, 
            empresa.cnpjEmpresa,
            empresa.enderecoEmpresa
        ');
        $this->db->from('sub_unidade');
        $this->db->join('unidade', 'unidade.idUnidade = sub_unidade.idUnidade', 'left');
        $this->db->join('empresa', 'empresa.idEmpresa = unidade.idEmpresa', 'left');
        $this->db->order_by('sub_unidade.nomeSubUnidade', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getUnidadesPorEmpresa($idEmpresa)
    {
        $this->db->select('*');
        $this->db->from('unidade');
        $this->db->where('idEmpresa', $idEmpresa);
        $this->db->order_by('nomeUnidade', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getEmpresaById($id)
    {
        $this->db->select('*');
        $this->db->from('empresa');
        $this->db->where('idEmpresa', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getUnidadeById($id)
    {
        $this->db->select('unidade.*, empresa.nomeEmpresa');
        $this->db->from('unidade');
        $this->db->join('empresa', 'empresa.idEmpresa = unidade.idEmpresa', 'left');
        $this->db->where('idUnidade', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getSubUnidadeById($id)
    {
        $this->db->select('
            sub_unidade.*, 
            unidade.nomeUnidade,
            unidade.idEmpresa,
            empresa.nomeEmpresa
        ');
        $this->db->from('sub_unidade');
        $this->db->join('unidade', 'unidade.idUnidade = sub_unidade.idUnidade', 'left');
        $this->db->join('empresa', 'empresa.idEmpresa = unidade.idEmpresa', 'left');
        $this->db->where('idSubUnidade', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }
}
