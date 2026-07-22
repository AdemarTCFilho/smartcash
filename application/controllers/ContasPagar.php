<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ContasPagar extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('contasPagar_model');
        $this->data['menuContasPagar'] = 'Contas a Pagar';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vContasPagar')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Contas a Pagar.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/contasPagar/gerenciar/';

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'contasPagar/contasPagar';
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

        if (empty($idClientes) || empty($idEmpresa) || empty($valor) || empty($vencimento)) {
            echo json_encode(['success' => false, 'message' => 'Cliente, Empresa, Valor e Vencimento são obrigatórios.']);
            return;
        }

        $data = [
            'idClientes' => $idClientes,
            'idUsuarios' => $this->session->userdata('id'),
            'idEmpresa' => $idEmpresa,
            'idUnidade' => $idUnidade ?: null,
            'idSubUnidade' => $idSubUnidade ?: null,
            'valor' => $this->formatDecimal($valor),
            'vencimento' => $vencimento,
            'unidade' => $this->input->post('unidade'),
            'idCategoria' => $this->input->post('idCategoria') ?: null,
            'observacoes' => $this->input->post('observacoes'),
        ];

        if ($id) {
            $this->contasPagar_model->edit('contas_pagar', $data, 'idContaPagar', $id);
            echo json_encode(['success' => true, 'message' => 'Conta atualizada com sucesso!']);
        } else {
            $this->contasPagar_model->add('contas_pagar', $data);
            echo json_encode(['success' => true, 'message' => 'Conta cadastrada com sucesso!']);
        }
    }

    public function listar()
    {
        $data = $this->contasPagar_model->getAllContasPagar();
        echo json_encode(['data' => $data]);
    }

    public function listarClientes()
    {
        $clientes = $this->contasPagar_model->getAllClientes();
        echo json_encode(['data' => $clientes]);
    }

    public function listarUsuarios()
    {
        $usuarios = $this->contasPagar_model->getAllUsuarios();
        echo json_encode(['data' => $usuarios]);
    }

    public function listarEmpresas()
    {
        $empresas = $this->contasPagar_model->getAllEmpresas();
        echo json_encode(['data' => $empresas]);
    }

    public function listarUnidadesPorEmpresa()
    {
        $idEmpresa = $this->input->get('idEmpresa');
        $unidades = $this->contasPagar_model->getUnidadesPorEmpresa($idEmpresa);
        echo json_encode(['data' => $unidades]);
    }

    public function listarSubUnidadesPorUnidade()
    {
        $idUnidade = $this->input->get('idUnidade');
        $subunidades = $this->contasPagar_model->getSubUnidadesPorUnidade($idUnidade);
        echo json_encode(['data' => $subunidades]);
    }

    public function listarCategorias()
    {
        $categorias = $this->contasPagar_model->getAllCategorias();
        echo json_encode(['data' => $categorias]);
    }

    public function getDadosDashboard()
    {
        $totalAPagar = $this->contasPagar_model->getTotalAPagar();
        $totalPago = $this->contasPagar_model->getTotalPago();
        $proximosVencimentos = $this->contasPagar_model->getProximosVencimentos(5);
        $despesasPorCategoria = $this->contasPagar_model->getDespesasPorCategoria();
        $proximoVencimento = null;
        if (!empty($proximosVencimentos)) {
            $proximoVencimento = $proximosVencimentos[0];
        }

        echo json_encode([
            'totalAPagar' => $totalAPagar,
            'totalPago' => $totalPago,
            'proximoVencimento' => $proximoVencimento,
            'proximosVencimentos' => $proximosVencimentos,
            'despesasPorCategoria' => $despesasPorCategoria,
        ]);
    }

    public function excluir()
    {
        $id = $this->input->post('id');
        $this->contasPagar_model->delete('contas_pagar', 'idContaPagar', $id);
        echo json_encode(['success' => true, 'message' => 'Conta excluída com sucesso!']);
    }

    public function getDados()
    {
        $id = $this->input->get('id');
        $data = $this->contasPagar_model->getContaPagarById($id);
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
