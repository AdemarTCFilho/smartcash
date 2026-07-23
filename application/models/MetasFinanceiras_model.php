<?php
class MetasFinanceiras_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getMetasConsolidadas($mesReferencia = null, $idEmpresa = null)
    {
        $sql = "
            SELECT
                COALESCE(SUM(metaReceita), 0) as totalMetaReceita,
                COALESCE(SUM(tetoDespesa), 0) as totalTetoDespesa,
                COALESCE(SUM(metaLucro), 0) as totalMetaLucro
            FROM projetado_realizado
            WHERE 1=1
        ";
        $params = [];
        if ($mesReferencia) {
            $sql .= " AND mesReferencia = ?";
            $params[] = $mesReferencia;
        }
        if ($idEmpresa) {
            $sql .= " AND idEmpresa = ?";
            $params[] = $idEmpresa;
        }
        return $this->db->query($sql, $params)->row();
    }

    public function getMetasPorUnidade($mesReferencia = null, $idEmpresa = null)
    {
        $sql = "
            SELECT
                pr.idProjReal,
                pr.idEmpresa,
                pr.idUnidade,
                pr.mesReferencia,
                pr.metaReceita,
                pr.tetoDespesa,
                COALESCE(pr.metaLucro, 0) as metaLucro,
                pr.observacoes,
                e.nomeEmpresa,
                u.nomeUnidade,
                COALESCE((
                    SELECT SUM(cr.valor)
                    FROM contas_receber cr
                    WHERE cr.idUnidade = pr.idUnidade
                      AND cr.vencimento BETWEEN CONCAT(pr.mesReferencia, '-01') AND LAST_DAY(CONCAT(pr.mesReferencia, '-01'))
                ), 0) as realizadoReceita
            FROM projetado_realizado pr
            JOIN empresa e ON e.idEmpresa = pr.idEmpresa
            JOIN unidade u ON u.idUnidade = pr.idUnidade
            WHERE 1=1
        ";
        $params = [];
        if ($mesReferencia) {
            $sql .= " AND pr.mesReferencia = ?";
            $params[] = $mesReferencia;
        }
        if ($idEmpresa) {
            $sql .= " AND pr.idEmpresa = ?";
            $params[] = $idEmpresa;
        }
        $sql .= " ORDER BY e.nomeEmpresa, u.nomeUnidade";
        return $this->db->query($sql, $params)->result();
    }

    public function getMetaById($id)
    {
        $sql = "
            SELECT
                pr.*,
                e.nomeEmpresa,
                u.nomeUnidade
            FROM projetado_realizado pr
            JOIN empresa e ON e.idEmpresa = pr.idEmpresa
            JOIN unidade u ON u.idUnidade = pr.idUnidade
            WHERE pr.idProjReal = ?
        ";
        return $this->db->query($sql, [$id])->row();
    }

    public function getAllEmpresas()
    {
        return $this->db->query("
            SELECT idEmpresa, nomeEmpresa
            FROM empresa
            WHERE status = 1
            ORDER BY nomeEmpresa ASC
        ")->result();
    }

    public function getSubUnidadesPorUnidade($idUnidade)
    {
        return $this->db->query("
            SELECT idSubUnidade, nomeSubUnidade
            FROM sub_unidade
            WHERE idUnidade = ? AND status = 1
            ORDER BY nomeSubUnidade ASC
        ", [$idUnidade])->result();
    }

    public function getUnidadesPorEmpresa($idEmpresa)
    {
        return $this->db->query("
            SELECT idUnidade, nomeUnidade
            FROM unidade
            WHERE idEmpresa = ? AND status = 1
            ORDER BY nomeUnidade ASC
        ", [$idEmpresa])->result();
    }

    public function add($data)
    {
        $salvar = [
            'idUsuarios' => $data['idUsuarios'],
            'idEmpresa' => $data['idEmpresa'],
            'idUnidade' => $data['idUnidade'],
            'idSubUnidade' => $data['idSubUnidade'] ?? null,
            'mesReferencia' => $data['mesReferencia'],
            'metaReceita' => $data['metaReceita'],
            'tetoDespesa' => $data['tetoDespesa'],
            'metaLucro' => $data['metaLucro'],
            'observacoes' => $data['observacoes'] ?? '',
        ];
        return $this->db->insert('projetado_realizado', $salvar);
    }

    public function edit($id, $data)
    {
        $atualizar = [
            'idEmpresa' => $data['idEmpresa'],
            'idUnidade' => $data['idUnidade'],
            'idSubUnidade' => $data['idSubUnidade'] ?? null,
            'mesReferencia' => $data['mesReferencia'],
            'metaReceita' => $data['metaReceita'],
            'tetoDespesa' => $data['tetoDespesa'],
            'metaLucro' => $data['metaLucro'],
            'observacoes' => $data['observacoes'] ?? '',
        ];
        $this->db->where('idProjReal', $id);
        return $this->db->update('projetado_realizado', $atualizar);
    }

    public function delete($id)
    {
        $this->db->where('idProjReal', $id);
        return $this->db->delete('projetado_realizado');
    }
}
