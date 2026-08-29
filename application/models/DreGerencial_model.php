<?php
class DreGerencial_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getTotalReceitas($mes, $idUnidade = null, $idEmpresa = null)
    {
        $this->db->select('COALESCE(SUM(valor), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_receber');
        $this->db->where("DATE_FORMAT(vencimento, '%Y-%m') = ", $mes);
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        if ($idEmpresa) $this->db->where('idEmpresa', $idEmpresa);
        $principal = $this->db->get()->row();

        $importado = $this->getTotalImportacao('C', $mes, $idUnidade, $idEmpresa);

        return (object) [
            'total' => (float) $principal->total + (float) $importado->total,
            'qtd' => (int) $principal->qtd + (int) $importado->qtd,
        ];
    }

    public function getTotalDespesas($mes, $idUnidade = null, $idEmpresa = null)
    {
        $this->db->select('COALESCE(SUM(valor), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_pagar');
        $this->db->where("DATE_FORMAT(vencimento, '%Y-%m') = ", $mes);
        if ($idUnidade) $this->db->where('idUnidade', $idUnidade);
        if ($idEmpresa) $this->db->where('idEmpresa', $idEmpresa);
        $principal = $this->db->get()->row();

        $importado = $this->getTotalImportacao('D', $mes, $idUnidade, $idEmpresa);

        return (object) [
            'total' => (float) $principal->total + (float) $importado->total,
            'qtd' => (int) $principal->qtd + (int) $importado->qtd,
        ];
    }

    public function getReceitasPorCategoria($mes, $idUnidade = null, $idEmpresa = null)
    {
        $this->db->select('c.idCategoria, c.nomeCategoria, COALESCE(SUM(cr.valor), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_receber cr');
        $this->db->join('categoria c', 'c.idCategoria = cr.idCategoria');
        $this->db->where("DATE_FORMAT(cr.vencimento, '%Y-%m') = ", $mes);
        if ($idUnidade) $this->db->where('cr.idUnidade', $idUnidade);
        if ($idEmpresa) $this->db->where('cr.idEmpresa', $idEmpresa);
        $this->db->group_by('c.idCategoria, c.nomeCategoria');
        $principal = $this->db->get()->result();

        $importado = $this->getPorCategoriaImportacao('C', $mes, $idUnidade, $idEmpresa);

        return $this->mesclarPorCategoria($principal, $importado);
    }

    public function getDespesasPorCategoria($mes, $idUnidade = null, $idEmpresa = null)
    {
        $this->db->select('c.idCategoria, c.nomeCategoria, COALESCE(SUM(cp.valor), 0) as total, COUNT(*) as qtd');
        $this->db->from('contas_pagar cp');
        $this->db->join('categoria c', 'c.idCategoria = cp.idCategoria');
        $this->db->where("DATE_FORMAT(cp.vencimento, '%Y-%m') = ", $mes);
        if ($idUnidade) $this->db->where('cp.idUnidade', $idUnidade);
        if ($idEmpresa) $this->db->where('cp.idEmpresa', $idEmpresa);
        $this->db->group_by('c.idCategoria, c.nomeCategoria');
        $principal = $this->db->get()->result();

        $importado = $this->getPorCategoriaImportacao('D', $mes, $idUnidade, $idEmpresa);

        return $this->mesclarPorCategoria($principal, $importado);
    }

    private function getTotalImportacao($status, $mes, $idUnidade = null, $idEmpresa = null)
    {
        $tipo = $status === 'C' ? 'ENTRADA' : 'SAIDA';

        $this->db->select('COALESCE(SUM(di.valor), 0) as total, COUNT(*) as qtd');
        $this->db->from('dre_importacao di');
        $this->db->join('categoria c', 'c.idCategoria = di.idCategoria');
        $this->db->where('di.status', $status);
        $this->db->where('c.tipo', $tipo);
        $this->db->where("DATE_FORMAT(di.data, '%Y-%m') = ", $mes);
        $this->filtrarImportacaoCompleta();
        if ($idUnidade) $this->db->where('di.idUnidade', $idUnidade);
        if ($idEmpresa) {
            $this->db->join('unidade u', 'u.idUnidade = di.idUnidade');
            $this->db->where('u.idEmpresa', $idEmpresa);
        }
        return $this->db->get()->row();
    }

    private function getPorCategoriaImportacao($status, $mes, $idUnidade = null, $idEmpresa = null)
    {
        $tipo = $status === 'C' ? 'ENTRADA' : 'SAIDA';

        $this->db->select('c.idCategoria, c.nomeCategoria, COALESCE(SUM(di.valor), 0) as total, COUNT(*) as qtd');
        $this->db->from('dre_importacao di');
        $this->db->join('categoria c', 'c.idCategoria = di.idCategoria');
        $this->db->where('di.status', $status);
        $this->db->where('c.tipo', $tipo);
        $this->db->where("DATE_FORMAT(di.data, '%Y-%m') = ", $mes);
        $this->filtrarImportacaoCompleta();
        if ($idUnidade) $this->db->where('di.idUnidade', $idUnidade);
        if ($idEmpresa) {
            $this->db->join('unidade u', 'u.idUnidade = di.idUnidade');
            $this->db->where('u.idEmpresa', $idEmpresa);
        }
        $this->db->group_by('c.idCategoria, c.nomeCategoria');
        return $this->db->get()->result();
    }

    private function filtrarImportacaoCompleta()
    {
        // Colunas "DESPESAS" e "CENTRO DE CUSTO" da tela de DRE Importação são, na
        // prática, os selects de mapeamento de Subcategoria (Despesas) e Unidade
        // (Centro de Custo) — idCategoria já é exigido via o INNER JOIN com categoria.
        $this->db->where('di.idSubCategoria IS NOT NULL');
        $this->db->where('di.idUnidade IS NOT NULL');
    }

    private function mesclarPorCategoria($listaPrincipal, $listaImportada)
    {
        $mapa = [];
        foreach ($listaPrincipal as $item) {
            $mapa[$item->idCategoria] = (object) [
                'idCategoria' => $item->idCategoria,
                'nomeCategoria' => $item->nomeCategoria,
                'total' => (float) $item->total,
                'qtd' => (int) $item->qtd,
            ];
        }
        foreach ($listaImportada as $item) {
            if (isset($mapa[$item->idCategoria])) {
                $mapa[$item->idCategoria]->total += (float) $item->total;
                $mapa[$item->idCategoria]->qtd += (int) $item->qtd;
            } else {
                $mapa[$item->idCategoria] = (object) [
                    'idCategoria' => $item->idCategoria,
                    'nomeCategoria' => $item->nomeCategoria,
                    'total' => (float) $item->total,
                    'qtd' => (int) $item->qtd,
                ];
            }
        }

        $resultado = array_values($mapa);
        usort($resultado, function ($a, $b) {
            return $b->total <=> $a->total;
        });
        return $resultado;
    }

    public function getComparativo($mes, $idUnidade = null, $idEmpresa = null)
    {
        $dt = new DateTime($mes . '-01');
        $mesAtual = $dt->format('Y-m');
        $dt->modify('-1 month');
        $mesAnterior = $dt->format('Y-m');

        $recAtual = $this->getTotalReceitas($mesAtual, $idUnidade, $idEmpresa);
        $despAtual = $this->getTotalDespesas($mesAtual, $idUnidade, $idEmpresa);
        $recAnt = $this->getTotalReceitas($mesAnterior, $idUnidade, $idEmpresa);
        $despAnt = $this->getTotalDespesas($mesAnterior, $idUnidade, $idEmpresa);

        return [
            'mesAtual' => $mesAtual,
            'mesAnterior' => $mesAnterior,
            'receitas' => [
                'atual' => (float)$recAtual->total,
                'anterior' => (float)$recAnt->total,
            ],
            'despesas' => [
                'atual' => (float)$despAtual->total,
                'anterior' => (float)$despAnt->total,
            ],
            'resultado' => [
                'atual' => (float)$recAtual->total - (float)$despAtual->total,
                'anterior' => (float)$recAnt->total - (float)$despAnt->total,
            ],
        ];
    }

    public function getEvolucao($mesFim, $idUnidade = null, $idEmpresa = null)
    {
        $dt = new DateTime($mesFim . '-01');
        $dados = [];
        for ($i = 0; $i < 12; $i++) {
            $mes = $dt->format('Y-m');
            $rec = $this->getTotalReceitas($mes, $idUnidade, $idEmpresa);
            $desp = $this->getTotalDespesas($mes, $idUnidade, $idEmpresa);
            $dados[] = [
                'mes' => $mes,
                'receitas' => (float)$rec->total,
                'despesas' => (float)$desp->total,
                'resultado' => (float)$rec->total - (float)$desp->total,
            ];
            $dt->modify('-1 month');
        }
        return array_reverse($dados);
    }

    public function getAllEmpresas()
    {
        return $this->db->query("SELECT idEmpresa, nomeEmpresa FROM empresa WHERE status = 1 ORDER BY nomeEmpresa ASC")->result();
    }

    public function getUnidadesPorEmpresa($idEmpresa)
    {
        if ($idEmpresa) {
            return $this->db->query("SELECT idUnidade, nomeUnidade FROM unidade WHERE idEmpresa = ? AND status = 1 ORDER BY nomeUnidade ASC", [$idEmpresa])->result();
        }
        return $this->db->query("SELECT idUnidade, nomeUnidade, idEmpresa FROM unidade WHERE status = 1 ORDER BY nomeEmpresa, nomeUnidade ASC")->result();
    }
}
