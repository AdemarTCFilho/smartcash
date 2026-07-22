<?php
class ConciliacaoBancaria_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($filtros = [])
    {
        $this->db->select('
            cb.*,
            c.nome as nomeConta,
            c.banco,
            u.nome as nomeUsuario
        ');
        $this->db->from('conciliacao_bancaria cb');
        $this->db->join('contas_bancarias c', 'c.idContaBancaria = cb.idContaBancaria', 'left');
        $this->db->join('usuarios u', 'u.idUsuarios = cb.idUsuarios', 'left');

        if (!empty($filtros['idContaBancaria'])) {
            $this->db->where('cb.idContaBancaria', $filtros['idContaBancaria']);
        }
        if (!empty($filtros['dataInicio'])) {
            $this->db->where('cb.data >=', $filtros['dataInicio']);
        }
        if (!empty($filtros['dataFim'])) {
            $this->db->where('cb.data <=', $filtros['dataFim']);
        }
        if (!empty($filtros['naoConciliadas'])) {
            $this->db->where('cb.conciliada', 0);
        }

        $this->db->order_by('cb.data', 'desc');
        $this->db->order_by('cb.idConciliacaoBancaria', 'desc');
        return $this->db->get()->result();
    }

    public function getById($id)
    {
        return $this->db->get_where('conciliacao_bancaria', ['idConciliacaoBancaria' => $id])->row();
    }

    public function add($data)
    {
        return $this->db->insert('conciliacao_bancaria', $data);
    }

    public function edit($id, $data)
    {
        $this->db->where('idConciliacaoBancaria', $id);
        return $this->db->update('conciliacao_bancaria', $data);
    }

    public function delete($id)
    {
        $this->db->where('idConciliacaoBancaria', $id);
        return $this->db->delete('conciliacao_bancaria');
    }

    public function getResumo($idContaBancaria, $dataInicio = null, $dataFim = null)
    {
        $this->db->select("
            COALESCE(SUM(CASE WHEN tipo = 'Entrada' THEN valor ELSE 0 END), 0) as totalEntradas,
            COALESCE(SUM(CASE WHEN tipo = 'Saída' THEN valor ELSE 0 END), 0) as totalSaidas
        ");
        $this->db->from('conciliacao_bancaria');
        $this->db->where('idContaBancaria', $idContaBancaria);
        if ($dataInicio) $this->db->where('data >=', $dataInicio);
        if ($dataFim) $this->db->where('data <=', $dataFim);
        return $this->db->get()->row();
    }

    public function getSaldoConta($idContaBancaria)
    {
        $this->db->select('saldoInicial');
        $this->db->from('contas_bancarias');
        $this->db->where('idContaBancaria', $idContaBancaria);
        return $this->db->get()->row();
    }

    public function conciliar($id)
    {
        $this->db->where('idConciliacaoBancaria', $id);
        return $this->db->update('conciliacao_bancaria', ['conciliada' => 1]);
    }

    public function getAllContas()
    {
        $this->db->select('idContaBancaria, nome, banco, conta');
        $this->db->from('contas_bancarias');
        $this->db->order_by('nome', 'asc');
        return $this->db->get()->result();
    }

    public function getContaById($id)
    {
        $this->db->select('idContaBancaria, nome, banco, conta, saldoInicial');
        $this->db->from('contas_bancarias');
        $this->db->where('idContaBancaria', $id);
        return $this->db->get()->row();
    }
}
