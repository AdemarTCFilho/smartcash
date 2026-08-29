<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DreImportacao_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function inserirLote($registros)
    {
        if (empty($registros)) {
            return 0;
        }
        $this->db->insert_batch('dre_importacao', $registros);
        return $this->db->insert_id();
    }

    public function listarPorStatus($status)
    {
        $this->db->select('di.*, c.nomeCategoria, sc.nomeSubCategoria, u.nomeUnidade');
        $this->db->from('dre_importacao di');
        $this->db->join('categoria c', 'c.idCategoria = di.idCategoria', 'left');
        $this->db->join('subcategoria sc', 'sc.idSubCategoria = di.idSubCategoria', 'left');
        $this->db->join('unidade u', 'u.idUnidade = di.idUnidade', 'left');
        $this->db->where('di.status', $status);
        $this->db->where('di.idUsuario', $this->session->userdata('id'));
        $this->db->order_by('di.data', 'ASC');
        return $this->db->get()->result();
    }

    public function contarPorStatus($status)
    {
        $this->db->where('status', $status);
        $this->db->where('idUsuario', $this->session->userdata('id'));
        return $this->db->count_all_results('dre_importacao');
    }

    public function atualizarMapeamento($id, $idCategoria, $idSubCategoria, $idUnidade)
    {
        $data = [];
        if ($idCategoria !== null) {
            $data['idCategoria'] = $idCategoria;
        }
        if ($idSubCategoria !== null) {
            $data['idSubCategoria'] = $idSubCategoria;
        }
        if ($idUnidade !== null) {
            $data['idUnidade'] = $idUnidade;
        }
        if (empty($data)) {
            return false;
        }
        $this->db->where('idImportacao', $id);
        $this->db->where('idUsuario', $this->session->userdata('id'));
        return $this->db->update('dre_importacao', $data);
    }

    public function excluirTodos()
    {
        $this->db->where('idUsuario', $this->session->userdata('id'));
        return $this->db->delete('dre_importacao');
    }

    public function getCategorias($busca = '', $tipo = '')
    {
        $this->db->select('sc.idSubCategoria, sc.nomeSubCategoria, sc.idCategoria, c.nomeCategoria, c.tipo');
        $this->db->from('subcategoria sc');
        $this->db->join('categoria c', 'c.idCategoria = sc.idCategoria', 'left');
        $this->db->where('sc.status', 1);
        if ($tipo && in_array($tipo, ['ENTRADA', 'SAIDA'])) {
            $this->db->where('c.tipo', $tipo);
        }
        if ($busca) {
            $this->db->like('sc.nomeSubCategoria', $busca);
        }
        $this->db->order_by('sc.nomeSubCategoria', 'ASC');
        $this->db->limit(50);
        return $this->db->get()->result();
    }

    public function getUnidades($busca = '')
    {
        $this->db->select('idUnidade, nomeUnidade');
        $this->db->from('unidade');
        $this->db->where('status', 1);
        if ($busca) {
            $this->db->like('nomeUnidade', $busca);
        }
        $this->db->order_by('nomeUnidade', 'ASC');
        $this->db->limit(50);
        return $this->db->get()->result();
    }
}
