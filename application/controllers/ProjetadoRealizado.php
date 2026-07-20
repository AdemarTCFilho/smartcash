<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ProjetadoRealizado extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('ProjetadoRealizado_model');
        $this->data['menuProjetadoRealizado'] = 'Projetado x Realizado';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vProjetadoRealizado')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Projetado x Realizado.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/projetadoRealizado/gerenciar/';

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'projetadoRealizado/projetadoRealizado';
        return $this->layout();
    }

    public function salvar()
    {
        $id = $this->input->post('id');

        $receita = $this->input->post('receitaProjetada');
        $meta = $this->input->post('metaReceita');
        $despesa = $this->input->post('despesaProjetada');
        $teto = $this->input->post('tetoDespesa');

        $data = [
            'idUsuarios' => $this->session->userdata('id'),
            'idEmpresa' => $this->input->post('idEmpresa'),
            'idUnidade' => $this->input->post('idUnidade'),
            'idSubUnidade' => $this->input->post('idSubUnidade') ?: null,
            'mesReferencia' => $this->input->post('mesReferencia'),
            'receitaProjetada' => $this->formatDecimal($receita),
            'metaReceita' => $this->formatDecimal($meta),
            'despesaProjetada' => $this->formatDecimal($despesa),
            'tetoDespesa' => $this->formatDecimal($teto),
            'observacoes' => $this->input->post('observacoes'),
        ];

        if ($id) {
            $this->ProjetadoRealizado_model->edit('projetado_realizado', $data, 'idProjReal', $id);
            echo json_encode(['success' => true, 'message' => 'Projeção atualizada com sucesso!']);
        } else {
            $this->ProjetadoRealizado_model->add('projetado_realizado', $data);
            echo json_encode(['success' => true, 'message' => 'Projeção criada com sucesso!']);
        }
    }

    public function listar()
    {
        $data = $this->ProjetadoRealizado_model->getAllProjReal();
        echo json_encode(['data' => $data]);
    }

    public function listarEmpresas()
    {
        $empresas = $this->ProjetadoRealizado_model->getAllEmpresas();
        echo json_encode(['data' => $empresas]);
    }

    public function listarUnidadesPorEmpresa()
    {
        $idEmpresa = $this->input->get('idEmpresa');
        $unidades = $this->ProjetadoRealizado_model->getUnidadesPorEmpresa($idEmpresa);
        echo json_encode(['data' => $unidades]);
    }

    public function listarSubUnidadesPorUnidade()
    {
        $idUnidade = $this->input->get('idUnidade');
        $subunidades = $this->ProjetadoRealizado_model->getSubUnidadesPorUnidade($idUnidade);
        echo json_encode(['data' => $subunidades]);
    }

    public function excluir()
    {
        $id = $this->input->post('id');
        $this->ProjetadoRealizado_model->delete('projetado_realizado', 'idProjReal', $id);
        echo json_encode(['success' => true, 'message' => 'Projeção excluída com sucesso!']);
    }

    public function getDados()
    {
        $id = $this->input->get('id');
        $data = $this->ProjetadoRealizado_model->getProjRealById($id);
        echo json_encode($data);
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
