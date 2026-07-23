<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ContasReceber extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('contasReceber_model');
        $this->data['menuContasReceber'] = 'Contas a Receber';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vContasReceber')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Contas a Receber.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/contasReceber/gerenciar/';

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'contasReceber/contasReceber';
        return $this->layout();
    }

    public function salvar()
    {
        $id = $this->input->post('id');

        $idClientes = $this->input->post('idClientes');
        $idEmpresa = $this->input->post('idEmpresa');
        $idUnidade = $this->input->post('idUnidade');
        $idSubUnidade = $this->input->post('idSubUnidade');
        $valor = $this->input->post('valor');
        $vencimento = $this->input->post('vencimento');

        if (empty($idClientes) || empty($idEmpresa) || empty($idUnidade) || empty($valor) || empty($vencimento)) {
            echo json_encode(['success' => false, 'message' => 'Cliente, Empresa, Unidade, Valor e Vencimento são obrigatórios.']);
            return;
        }

        $data = [
            'idClientes' => $idClientes,
            'idUsuarios' => $this->session->userdata('id'),
            'idEmpresa' => $idEmpresa,
            'idUnidade' => $idUnidade,
            'idSubUnidade' => $idSubUnidade ?: null,
            'valor' => $this->formatDecimal($valor),
            'vencimento' => $vencimento,
            'unidade' => $this->input->post('unidade'),
            'idCategoria' => $this->input->post('idCategoria') ?: null,
            'observacoes' => $this->input->post('observacoes'),
            'status' => 'pendente',
        ];

        if ($id) {
            $this->contasReceber_model->edit('contas_receber', $data, 'idContaReceber', $id);
            echo json_encode(['success' => true, 'message' => 'Conta atualizada com sucesso!']);
        } else {
            $this->contasReceber_model->add('contas_receber', $data);
            echo json_encode(['success' => true, 'message' => 'Conta cadastrada com sucesso!']);
        }
    }

    public function listar()
    {
        $data = $this->contasReceber_model->getAllContasReceber();
        echo json_encode(['data' => $data]);
    }

    public function listarClientes()
    {
        $clientes = $this->contasReceber_model->getAllClientes();
        echo json_encode(['data' => $clientes]);
    }

    public function listarUsuarios()
    {
        $usuarios = $this->contasReceber_model->getAllUsuarios();
        echo json_encode(['data' => $usuarios]);
    }

    public function listarEmpresas()
    {
        $empresas = $this->contasReceber_model->getAllEmpresas();
        echo json_encode(['data' => $empresas]);
    }

    public function listarUnidadesPorEmpresa()
    {
        $idEmpresa = $this->input->get('idEmpresa');
        $unidades = $this->contasReceber_model->getUnidadesPorEmpresa($idEmpresa);
        echo json_encode(['data' => $unidades]);
    }

    public function listarSubUnidadesPorUnidade()
    {
        $idUnidade = $this->input->get('idUnidade');
        $subunidades = $this->contasReceber_model->getSubUnidadesPorUnidade($idUnidade);
        echo json_encode(['data' => $subunidades]);
    }

    public function listarCategorias()
    {
        $categorias = $this->contasReceber_model->getAllCategorias();
        echo json_encode(['data' => $categorias]);
    }

    public function getDadosDashboard()
    {
        $totalAReceber = $this->contasReceber_model->getTotalAReceber();
        $totalRecebido = $this->contasReceber_model->getTotalRecebido();
        $proximosVencimentos = $this->contasReceber_model->getProximosVencimentos(5);
        $receitasPorCategoria = $this->contasReceber_model->getReceitasPorCategoria();
        $proximoVencimento = null;
        if (!empty($proximosVencimentos)) {
            $proximoVencimento = $proximosVencimentos[0];
        }

        echo json_encode([
            'totalAReceber' => $totalAReceber,
            'totalRecebido' => $totalRecebido,
            'proximoVencimento' => $proximoVencimento,
            'proximosVencimentos' => $proximosVencimentos,
            'receitasPorCategoria' => $receitasPorCategoria,
        ]);
    }

    public function excluir()
    {
        $id = $this->input->post('id');
        $this->contasReceber_model->delete('contas_receber', 'idContaReceber', $id);
        echo json_encode(['success' => true, 'message' => 'Conta excluída com sucesso!']);
    }

    public function getDados()
    {
        $id = $this->input->get('id');
        $data = $this->contasReceber_model->getContaReceberById($id);
        echo json_encode($data);
    }

    private function formatDecimal($value)
    {
        if (!$value) {
            return 0.00;
        }
        $value = preg_replace('/[R$\s]/', '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return (float) $value;
    }
}
