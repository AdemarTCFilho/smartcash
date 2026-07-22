<?php
class FluxoCaixa_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSaldoBancarioAtual($idContaBancaria = null)
    {
        $this->db->select('COALESCE(SUM(saldoInicial), 0) as total');
        $this->db->from('contas_bancarias');
        if ($idContaBancaria) $this->db->where('idContaBancaria', $idContaBancaria);
        return $this->db->get()->row();
    }

    public function getEntradasRealizadas($dataInicio = null, $dataFim = null, $idUnidade = null, $idContaBancaria = null)
    {
        $this->db->select('COALESCE(SUM(valorRecebido), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_receber');
        $this->db->where('status', 'liquidado');
        if ($dataInicio) $this->db->where('dataRecebimento >=', $dataInicio);
        if ($dataFim) $this->db->where('dataRecebimento <=', $dataFim);
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        return $this->db->get()->row();
    }

    public function getSaidasRealizadas($dataInicio = null, $dataFim = null, $idUnidade = null, $idContaBancaria = null)
    {
        $this->db->select('COALESCE(SUM(valorPago), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_pagar');
        $this->db->where('status', 'liquidado');
        if ($dataInicio) $this->db->where('dataPagamento >=', $dataInicio);
        if ($dataFim) $this->db->where('dataPagamento <=', $dataFim);
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        return $this->db->get()->row();
    }

    public function getAReceberPrevisto($idUnidade = null)
    {
        $this->db->select('COALESCE(SUM(valor - valorRecebido), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_receber');
        $this->db->where('status', 'pendente');
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        return $this->db->get()->row();
    }

    public function getAPagarPrevisto($idUnidade = null)
    {
        $this->db->select('COALESCE(SUM(valor - valorPago), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_pagar');
        $this->db->where('status', 'pendente');
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        return $this->db->get()->row();
    }

    public function getInadimplencia($idUnidade = null)
    {
        $this->db->select('COALESCE(SUM(valor - valorRecebido), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_receber');
        $this->db->where('status', 'pendente');
        $this->db->where('vencimento <', date('Y-m-d'));
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        return $this->db->get()->row();
    }

    public function getContasVencidas($idUnidade = null)
    {
        $this->db->select('COUNT(*) as qtd');
        $this->db->from('contas_pagar');
        $this->db->where('status', 'pendente');
        $this->db->where('vencimento <', date('Y-m-d'));
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        return $this->db->get()->row();
    }

    public function getFluxoDiario($dataInicio, $dataFim, $idUnidade = null)
    {
        $dias = [];
        $inicio = new DateTime($dataInicio);
        $fim = new DateTime($dataFim);
        $fim->modify('+1 day');

        $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim);

        $entradasRaw = $this->db->query("
            SELECT dataRecebimento as data, SUM(valorRecebido) as total
            FROM contas_receber
            WHERE status = 'liquidado'
                AND dataRecebimento BETWEEN ? AND ?
                " . ($idUnidade ? "AND idUnidade = ?" : "") . "
            GROUP BY dataRecebimento
            ORDER BY dataRecebimento ASC
        ", array_merge([$dataInicio, $dataFim], $idUnidade ? [$idUnidade] : []))->result();

        $saidasRaw = $this->db->query("
            SELECT dataPagamento as data, SUM(valorPago) as total
            FROM contas_pagar
            WHERE status = 'liquidado'
                AND dataPagamento BETWEEN ? AND ?
                " . ($idUnidade ? "AND idUnidade = ?" : "") . "
            GROUP BY dataPagamento
            ORDER BY dataPagamento ASC
        ", array_merge([$dataInicio, $dataFim], $idUnidade ? [$idUnidade] : []))->result();

        $entradasMap = [];
        foreach ($entradasRaw as $e) {
            $entradasMap[$e->data] = (float)$e->total;
        }
        $saidasMap = [];
        foreach ($saidasRaw as $s) {
            $saidasMap[$s->data] = (float)$s->total;
        }

        $saldoAcumulado = 0;
        foreach ($periodo as $dt) {
            $d = $dt->format('Y-m-d');
            $ent = $entradasMap[$d] ?? 0;
            $sai = $saidasMap[$d] ?? 0;
            $saldoAcumulado += ($ent - $sai);
            $dias[] = [
                'data' => $d,
                'entradas' => $ent,
                'saidas' => $sai,
                'saldo' => $saldoAcumulado,
            ];
        }

        return $dias;
    }

    public function getAllUnidades()
    {
        $this->db->select('idUnidade, nomeUnidade');
        $this->db->from('unidade');
        $this->db->where('status', 1);
        $this->db->order_by('nomeUnidade', 'asc');
        return $this->db->get()->result();
    }

    public function getAllContasBancarias()
    {
        $this->db->select('idContaBancaria, nome, banco, conta, saldoInicial');
        $this->db->from('contas_bancarias');
        $this->db->order_by('nome', 'asc');
        return $this->db->get()->result();
    }

    public function getEntradasPorUnidade($dataInicio, $dataFim)
    {
        $this->db->select('
            unidade.nomeUnidade,
            COALESCE(SUM(contas_receber.valorRecebido), 0) as total
        ');
        $this->db->from('unidade');
        $this->db->join('contas_receber', 'contas_receber.idUnidade = unidade.idUnidade AND contas_receber.status = "liquidado" AND contas_receber.dataRecebimento BETWEEN ' . $this->db->escape($dataInicio) . ' AND ' . $this->db->escape($dataFim), 'left');
        $this->db->where('unidade.status', 1);
        $this->db->group_by('unidade.idUnidade');
        $this->db->order_by('total', 'desc');
        return $this->db->get()->result();
    }

    public function getSaidasPorUnidade($dataInicio, $dataFim)
    {
        $this->db->select('
            unidade.nomeUnidade,
            COALESCE(SUM(contas_pagar.valorPago), 0) as total
        ');
        $this->db->from('unidade');
        $this->db->join('contas_pagar', 'contas_pagar.idUnidade = unidade.idUnidade AND contas_pagar.status = "liquidado" AND contas_pagar.dataPagamento BETWEEN ' . $this->db->escape($dataInicio) . ' AND ' . $this->db->escape($dataFim), 'left');
        $this->db->where('unidade.status', 1);
        $this->db->group_by('unidade.idUnidade');
        $this->db->order_by('total', 'desc');
        return $this->db->get()->result();
    }
}
