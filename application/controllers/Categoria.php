<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Categoria extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('Categoria_model');
        $this->data['menuvCategoria'] = 'Categoria';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vCategoria')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Categorias.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/categoria/gerenciar/';

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'categoria/categoria';
        return $this->layout();
    }

    public function salvar()
    {
        $id = $this->input->post('id');
        $nomeCategoria = $this->input->post('nomeCategoria');
        $tipo = $this->input->post('tipo');
        $descricaoCategoria = $this->input->post('descricaoCategoria');

        if (empty($nomeCategoria) || empty($descricaoCategoria)) {
            echo json_encode(['success' => false, 'message' => 'Nome e descrição são obrigatórios.']);
            return;
        }

        if (strlen($nomeCategoria) > 100) {
            echo json_encode(['success' => false, 'message' => 'Nome deve ter no máximo 100 caracteres.']);
            return;
        }

        if (strlen($descricaoCategoria) > 255) {
            echo json_encode(['success' => false, 'message' => 'Descrição deve ter no máximo 255 caracteres.']);
            return;
        }

        if (!in_array($tipo, ['ENTRADA', 'SAIDA'])) {
            echo json_encode(['success' => false, 'message' => 'Tipo deve ser ENTRADA ou SAÍDA.']);
            return;
        }

        $status = $this->input->post('status') ? 1 : 0;

        $data = [
            'nomeCategoria' => $nomeCategoria,
            'tipo' => $tipo,
            'descricaoCategoria' => $descricaoCategoria,
            'status' => $status,
        ];

        if ($id) {
            $this->Categoria_model->edit('categoria', $data, 'idCategoria', $id);
            echo json_encode(['success' => true, 'message' => 'Categoria atualizada com sucesso!']);
        } else {
            $this->Categoria_model->add('categoria', $data);
            echo json_encode(['success' => true, 'message' => 'Categoria cadastrada com sucesso!']);
        }
    }

    public function listar()
    {
        $categorias = $this->Categoria_model->getAllCategorias();
        echo json_encode(['data' => $categorias]);
    }

    public function excluir()
    {
        $id = $this->input->post('id');
        $this->Categoria_model->delete('categoria', 'idCategoria', $id);
        echo json_encode(['success' => true, 'message' => 'Categoria excluída com sucesso!']);
    }

    public function getDados()
    {
        $id = $this->input->get('id');
        $categoria = $this->Categoria_model->getCategoriaById($id);
        echo json_encode($categoria);
    }

    public function salvarSubCategoria()
    {
        $id = $this->input->post('id');
        $idCategoria = $this->input->post('idCategoria');
        $nomeSubCategoria = $this->input->post('nomeSubCategoria');

        if (empty($idCategoria) || empty($nomeSubCategoria)) {
            echo json_encode(['success' => false, 'message' => 'Categoria e nome são obrigatórios.']);
            return;
        }

        if (strlen($nomeSubCategoria) > 100) {
            echo json_encode(['success' => false, 'message' => 'Nome deve ter no máximo 100 caracteres.']);
            return;
        }

        $status = $this->input->post('status') ? 1 : 0;

        $data = [
            'idCategoria' => $idCategoria,
            'nomeSubCategoria' => $nomeSubCategoria,
            'status' => $status,
        ];

        if ($id) {
            $this->Categoria_model->edit('subcategoria', $data, 'idSubCategoria', $id);
            echo json_encode(['success' => true, 'message' => 'SubCategoria atualizada com sucesso!']);
        } else {
            $this->Categoria_model->add('subcategoria', $data);
            echo json_encode(['success' => true, 'message' => 'SubCategoria cadastrada com sucesso!']);
        }
    }

    public function listarSubCategorias()
    {
        $subcategorias = $this->Categoria_model->getAllSubCategorias();
        echo json_encode(['data' => $subcategorias]);
    }

    public function excluirSubCategoria()
    {
        $id = $this->input->post('id');
        $this->Categoria_model->delete('subcategoria', 'idSubCategoria', $id);
        echo json_encode(['success' => true, 'message' => 'SubCategoria excluída com sucesso!']);
    }

    public function getDadosSubCategoria()
    {
        $id = $this->input->get('id');
        $subcategoria = $this->Categoria_model->getSubCategoriaById($id);
        echo json_encode($subcategoria);
    }

    public function listarCategoriasAtivas()
    {
        $categorias = $this->Categoria_model->getCategoriasAtivas();
        echo json_encode(['data' => $categorias]);
    }
}
