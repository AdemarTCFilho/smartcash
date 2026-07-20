<?php
class Usuarios_model extends CI_Model
{
    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */
    
    public function __construct()
    {
        parent::__construct();
    }

    

    public function get($perpage = 0, $start = 0, $one = false)
    {
        $this->db->from('usuarios');
        $this->db->select('usuarios.*, permissoes.nome as permissao, unidade_solicitante.nome as nome_unidade_solicitante');
        $this->db->limit($perpage, $start);
        $this->db->join('permissoes', 'usuarios.permissoes_id = permissoes.idPermissao', 'left');
        $this->db->join('unidade_solicitante', 'usuarios.idUnidadeSolicitante = unidade_solicitante.idUnidadeSolicitante', 'left');
  
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }

    public function getAllTipos()
    {
        $this->db->where('situacao', 1);
        return $this->db->get('tiposUsuario')->result();
    }

    public function getById($id)
    {
        $this->db->select('usuarios.*, unidade_solicitante.nome as nome_unidade_solicitante');
        $this->db->from('usuarios');
        $this->db->join('unidade_solicitante', 'usuarios.idUnidadeSolicitante = unidade_solicitante.idUnidadeSolicitante', 'left');
        $this->db->where('idUsuarios', $id);
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function getAll()
    {
        return $this->db->get('usuarios')->result();
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
    
    public function getAllUnidadesSolicitantes()
    {
        $this->db->where('situacao', 1);
        $this->db->order_by('nome', 'ASC');
        return $this->db->get('unidade_solicitante')->result();
    }
    
    public function count($table)
    {
        return $this->db->count_all($table);
    }
}
