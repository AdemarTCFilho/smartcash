<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MetasFinanceiras extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('MetasFinanceiras_model');
        $this->data['menuMetasFinanceiras'] = 'Metas Financeiras';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vMetasFinanceiras')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Metas Financeiras.');
            redirect(base_url());
        }

        $this->data['view'] = 'metasFinanceiras/metasFinanceiras';
        return $this->layout();
    }

    public function getDados()
    {
        header('Content-Type: application/json');
        try {
            $mesReferencia = $this->input->get('mes');
            $idEmpresa = $this->input->get('idEmpresa');

            if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vMetasFinanceiras')) {
                echo json_encode(['error' => 'Sem permissão']);
                exit;
            }

            $consolidado = $this->MetasFinanceiras_model->getMetasConsolidadas($mesReferencia, $idEmpresa);
            $metas = $this->MetasFinanceiras_model->getMetasPorUnidade($mesReferencia, $idEmpresa);

            echo json_encode([
                'consolidado' => $consolidado,
                'metas' => $metas,
            ], JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function salvar()
    {
        $id = $this->input->post('id');

        $data = [
            'idUsuarios' => $this->session->userdata('id'),
            'idEmpresa' => $this->input->post('idEmpresa'),
            'idUnidade' => $this->input->post('idUnidade'),
            'idSubUnidade' => $this->input->post('idSubUnidade') ?: null,
            'mesReferencia' => $this->input->post('mesReferencia'),
            'metaReceita' => $this->formatDecimal($this->input->post('metaReceita')),
            'tetoDespesa' => $this->formatDecimal($this->input->post('metaDespesa')),
            'metaLucro' => $this->formatDecimal($this->input->post('metaLucro')),
            'observacoes' => $this->input->post('observacoes'),
        ];

        if ($id) {
            $this->MetasFinanceiras_model->edit($id, $data);
            echo json_encode(['success' => true, 'message' => 'Meta atualizada com sucesso!']);
        } else {
            $this->MetasFinanceiras_model->add($data);
            echo json_encode(['success' => true, 'message' => 'Meta criada com sucesso!']);
        }
    }

    public function getDadosMeta()
    {
        $id = $this->input->get('id');
        $data = $this->MetasFinanceiras_model->getMetaById($id);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function excluir()
    {
        $id = $this->input->post('id');
        $this->MetasFinanceiras_model->delete($id);
        echo json_encode(['success' => true, 'message' => 'Meta excluída com sucesso!']);
    }

    public function listarEmpresas()
    {
        $empresas = $this->MetasFinanceiras_model->getAllEmpresas();
        echo json_encode(['data' => $empresas]);
    }

    public function listarUnidades()
    {
        $idEmpresa = $this->input->get('idEmpresa');
        $unidades = $this->MetasFinanceiras_model->getUnidadesPorEmpresa($idEmpresa);
        echo json_encode(['data' => $unidades]);
    }

    public function listarSubUnidades()
    {
        $idUnidade = $this->input->get('idUnidade');
        $subunidades = $this->MetasFinanceiras_model->getSubUnidadesPorUnidade($idUnidade);
        echo json_encode(['data' => $subunidades]);
    }

    public function exportarCSV()
    {
        $mesReferencia = $this->input->get('mes');
        $idEmpresa = $this->input->get('idEmpresa');

        $metas = $this->MetasFinanceiras_model->getMetasPorUnidade($mesReferencia, $idEmpresa);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="metas_financeiras.csv"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, ['Unidade', 'Empresa', 'Meta Receita', 'Meta Despesa', 'Meta Lucro', 'Realizado Receita', 'Atingimento (%)'], ';');

        foreach ($metas as $m) {
            $atingimento = $m->metaReceita > 0 ? round(($m->realizadoReceita / $m->metaReceita) * 100, 1) : 0;
            fputcsv($output, [
                $m->nomeUnidade,
                $m->nomeEmpresa,
                number_format($m->metaReceita, 2, ',', '.'),
                number_format($m->tetoDespesa, 2, ',', '.'),
                number_format($m->metaLucro, 2, ',', '.'),
                number_format($m->realizadoReceita, 2, ',', '.'),
                $atingimento . '%',
            ], ';');
        }

        fclose($output);
        exit;
    }

    private function formatDecimal($value)
    {
        if (!$value) return 0.00;
        $value = preg_replace('/[R$\s]/', '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return (float) $value;
    }
}
