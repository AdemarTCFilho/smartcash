<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class EmpresasUnidades extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('Empresas_model');
        $this->data['menuvEmpresasUnidades'] = 'Empresas / Unidades';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vEmpresasUnidades')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Empresas / Unidades.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/empresasUnidades/gerenciar/';

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'empresasUnidades/empresasUnidades';
        return $this->layout();
    }

    public function salvarEmpresa()
    {
        $id = $this->input->post('id');
        $data = [
            'nomeEmpresa' => $this->input->post('nomeEmpresa'),
            'cnpjEmpresa' => preg_replace('/\D/', '', $this->input->post('cnpjEmpresa')),
            'enderecoEmpresa' => $this->input->post('enderecoEmpresa'),
            'status' => $this->input->post('status'),
        ];

        if ($id) {
            $this->Empresas_model->edit('empresa', $data, 'idEmpresa', $id);
            echo json_encode(['success' => true, 'message' => 'Empresa atualizada com sucesso!']);
        } else {
            $this->Empresas_model->add('empresa', $data);
            echo json_encode(['success' => true, 'message' => 'Empresa cadastrada com sucesso!']);
        }
    }

    public function salvarUnidade()
    {
        $id = $this->input->post('id');
        $data = [
            'idEmpresa' => $this->input->post('idEmpresa'),
            'nomeUnidade' => $this->input->post('nomeUnidade'),
            'enderecoUnidade' => $this->input->post('enderecoUnidade'),
            'status' => $this->input->post('status'),
        ];

        if ($id) {
            $this->Empresas_model->edit('unidade', $data, 'idUnidade', $id);
            echo json_encode(['success' => true, 'message' => 'Unidade atualizada com sucesso!']);
        } else {
            $this->Empresas_model->add('unidade', $data);
            echo json_encode(['success' => true, 'message' => 'Unidade cadastrada com sucesso!']);
        }
    }

    public function salvarSubUnidade()
    {
        $id = $this->input->post('id');
        $data = [
            'idUnidade' => $this->input->post('idUnidade'),
            'nomeSubUnidade' => $this->input->post('nomeSubUnidade'),
            'status' => $this->input->post('status'),
        ];

        if ($id) {
            $this->Empresas_model->edit('sub_unidade', $data, 'idSubUnidade', $id);
            echo json_encode(['success' => true, 'message' => 'SubUnidade atualizada com sucesso!']);
        } else {
            $this->Empresas_model->add('sub_unidade', $data);
            echo json_encode(['success' => true, 'message' => 'SubUnidade cadastrada com sucesso!']);
        }
    }

    public function listarEmpresas()
    {
        $empresas = $this->Empresas_model->getAllEmpresas();
        echo json_encode(['data' => $empresas]);
    }

    public function listarUnidades()
    {
        $unidades = $this->Empresas_model->getAllUnidades();
        echo json_encode(['data' => $unidades]);
    }

    public function listarSubUnidades()
    {
        $subunidades = $this->Empresas_model->getAllSubUnidades();
        echo json_encode(['data' => $subunidades]);
    }

    public function listarUnidadesPorEmpresa()
    {
        $idEmpresa = $this->input->get('idEmpresa');
        $unidades = $this->Empresas_model->getUnidadesPorEmpresa($idEmpresa);
        echo json_encode(['data' => $unidades]);
    }

    public function excluirEmpresa()
    {
        $id = $this->input->post('id');
        $this->Empresas_model->delete('empresa', 'idEmpresa', $id);
        echo json_encode(['success' => true, 'message' => 'Empresa excluída com sucesso!']);
    }

    public function excluirUnidade()
    {
        $id = $this->input->post('id');
        $this->Empresas_model->delete('unidade', 'idUnidade', $id);
        echo json_encode(['success' => true, 'message' => 'Unidade excluída com sucesso!']);
    }

    public function excluirSubUnidade()
    {
        $id = $this->input->post('id');
        $this->Empresas_model->delete('sub_unidade', 'idSubUnidade', $id);
        echo json_encode(['success' => true, 'message' => 'SubUnidade excluída com sucesso!']);
    }

    public function getDadosEmpresa()
    {
        $id = $this->input->get('id');
        $empresa = $this->Empresas_model->getEmpresaById($id);
        echo json_encode($empresa);
    }

    public function getDadosUnidade()
    {
        $id = $this->input->get('id');
        $unidade = $this->Empresas_model->getUnidadeById($id);
        echo json_encode($unidade);
    }

    public function getDadosSubUnidade()
    {
        $id = $this->input->get('id');
        $subunidade = $this->Empresas_model->getSubUnidadeById($id);
        echo json_encode($subunidade);
    }
}
