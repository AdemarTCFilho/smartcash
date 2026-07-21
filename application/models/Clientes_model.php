<?php
class Clientes_model extends CI_Model
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

    public function getById($id)
    {
        $this->db->select('*');
        $this->db->from('clientes');
        $this->db->where('idClientes', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getAllClientes()
    {
        $this->db->select('*');
        $this->db->from('clientes');
        $this->db->order_by('nomeCliente', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getOsByCliente($id)
    {
        $this->db->select('*');
        $this->db->from('os');
        $this->db->where('clientes_id', $id);

        $query = $this->db->get();
        return $query->result();
    }

    public function getAllOsByClient($id)
    {
        $this->db->select('*');
        $this->db->from('os');
        $this->db->where('clientes_id', $id);

        $query = $this->db->get();
        return $query->result();
    }

    public function removeClientOs($os)
    {
        if (is_array($os)) {
            foreach ($os as $o) {
                $this->db->where('idOs', $o->idOs);
                $this->db->delete('os');
            }
        } else {
            $this->db->where('idOs', $os->idOs);
            $this->db->delete('os');
        }
    }

    public function getAllVendasByClient($id)
    {
        $this->db->select('*');
        $this->db->from('vendas');
        $this->db->where('clientes_id', $id);

        $query = $this->db->get();
        return $query->result();
    }

    public function removeClientVendas($vendas)
    {
        if (is_array($vendas)) {
            foreach ($vendas as $v) {
                $this->db->where('idVendas', $v->idVendas);
                $this->db->delete('vendas');
            }
        } else {
            $this->db->where('idVendas', $vendas->idVendas);
            $this->db->delete('vendas');
        }
    }
}
