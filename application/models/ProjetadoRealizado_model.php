<?php
class ProjetadoRealizado_model extends CI_Model
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

    public function getAllProjReal()
    {
        $this->db->select('
            projetado_realizado.*,
            empresa.nomeEmpresa,
            unidade.nomeUnidade,
            sub_unidade.nomeSubUnidade,
            usuarios.nome
        ');
        $this->db->from('projetado_realizado');
        $this->db->join('empresa', 'empresa.idEmpresa = projetado_realizado.idEmpresa', 'left');
        $this->db->join('unidade', 'unidade.idUnidade = projetado_realizado.idUnidade', 'left');
        $this->db->join('sub_unidade', 'sub_unidade.idSubUnidade = projetado_realizado.idSubUnidade', 'left');
        $this->db->join('usuarios', 'usuarios.idUsuarios = projetado_realizado.idUsuarios', 'left');
        $this->db->order_by('projetado_realizado.dataCriacao', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getProjRealById($id)
    {
        $this->db->select('
            projetado_realizado.*,
            empresa.nomeEmpresa,
            unidade.nomeUnidade,
            sub_unidade.nomeSubUnidade
        ');
        $this->db->from('projetado_realizado');
        $this->db->join('empresa', 'empresa.idEmpresa = projetado_realizado.idEmpresa', 'left');
        $this->db->join('unidade', 'unidade.idUnidade = projetado_realizado.idUnidade', 'left');
        $this->db->join('sub_unidade', 'sub_unidade.idSubUnidade = projetado_realizado.idSubUnidade', 'left');
        $this->db->where('idProjReal', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
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

    public function getResumoEmpresas($mesReferencia)
    {
        $this->db->select('
            empresa.idEmpresa,
            empresa.nomeEmpresa,
            COALESCE(SUM(pr.receitaProjetada), 0) as totalReceitaProjetada,
            COALESCE(SUM(pr.metaReceita), 0) as totalMetaReceita,
            COALESCE(SUM(pr.despesaProjetada), 0) as totalDespesaProjetada,
            COALESCE(SUM(pr.tetoDespesa), 0) as totalTetoDespesa
        ');
        $this->db->from('empresa');
        $this->db->join('projetado_realizado pr', 'pr.idEmpresa = empresa.idEmpresa AND pr.mesReferencia = \'' . $this->db->escape_str($mesReferencia) . '\'', 'left');
        $this->db->where('empresa.status', 1);
        $this->db->group_by('empresa.idEmpresa');
        $this->db->order_by('empresa.nomeEmpresa', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
}
