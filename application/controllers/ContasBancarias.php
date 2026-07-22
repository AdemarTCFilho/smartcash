<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ContasBancarias extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('contasBancarias_model');
        $this->data['menuContasBancarias'] = 'Contas Bancárias';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vContasBancarias')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Contas Bancárias.');
            redirect(base_url());
        }
        $this->data['view'] = 'contasBancarias/contasBancarias';
        return $this->layout();
    }

    public function getDados()
    {
        $contas = $this->contasBancarias_model->getAll();
        $saldo = $this->contasBancarias_model->getSaldoConsolidado();
        echo json_encode(['contas' => $contas, 'saldoConsolidado' => (float)$saldo]);
    }

    public function getEmpresas()
    {
        echo json_encode($this->contasBancarias_model->getAllEmpresas());
    }

    public function getUsuarios()
    {
        echo json_encode($this->contasBancarias_model->getAllUsuarios());
    }

    public function getUnidades()
    {
        $idEmpresa = $this->input->get('idEmpresa');
        if ($idEmpresa) {
            echo json_encode($this->contasBancarias_model->getUnidadesPorEmpresa($idEmpresa));
        } else {
            echo json_encode($this->contasBancarias_model->getAllUnidades());
        }
    }

    public function getSubUnidades()
    {
        $idUnidade = $this->input->get('idUnidade');
        echo json_encode($this->contasBancarias_model->getSubUnidadesPorUnidade($idUnidade));
    }

    public function adicionar()
    {
        $idSubUnidade = $this->input->post('idSubUnidade');
        $data = [
            'nome' => $this->input->post('nome'),
            'idEmpresa' => $this->input->post('idEmpresa'),
            'idUnidade' => $this->input->post('idUnidade'),
            'idSubUnidade' => $idSubUnidade ? $idSubUnidade : null,
            'banco' => $this->input->post('banco'),
            'agencia' => $this->input->post('agencia'),
            'conta' => $this->input->post('conta'),
            'tipo' => $this->input->post('tipo'),
            'saldoInicial' => $this->formatDecimal($this->input->post('saldoInicial')),
            'dataCriacao' => date('Y-m-d H:i:s'),
        ];

        if ($this->contasBancarias_model->add($data)) {
            echo json_encode(['success' => true, 'message' => 'Conta bancária adicionada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao adicionar conta bancária.']);
        }
    }

    public function editar()
    {
        $id = $this->input->post('idContaBancaria');
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID não informado.']);
            return;
        }

        $idSubUnidade = $this->input->post('idSubUnidade');
        $data = [
            'nome' => $this->input->post('nome'),
            'idEmpresa' => $this->input->post('idEmpresa'),
            'idUnidade' => $this->input->post('idUnidade'),
            'idSubUnidade' => $idSubUnidade ? $idSubUnidade : null,
            'banco' => $this->input->post('banco'),
            'agencia' => $this->input->post('agencia'),
            'conta' => $this->input->post('conta'),
            'tipo' => $this->input->post('tipo'),
            'saldoInicial' => $this->formatDecimal($this->input->post('saldoInicial')),
        ];

        if ($this->contasBancarias_model->edit($id, $data)) {
            echo json_encode(['success' => true, 'message' => 'Conta bancária atualizada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar conta bancária.']);
        }
    }

    public function excluir()
    {
        $id = $this->input->post('idContaBancaria');
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID não informado.']);
            return;
        }

        if ($this->contasBancarias_model->delete($id)) {
            echo json_encode(['success' => true, 'message' => 'Conta bancária excluída com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir conta bancária.']);
        }
    }

    public function getConta()
    {
        $id = $this->input->get('id');
        $conta = $this->contasBancarias_model->getById($id);
        echo json_encode($conta);
    }

    private function formatDecimal($value)
    {
        if (!$value) return 0.00;
        $value = preg_replace('/[R$\s]/', '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return (float)$value;
    }
}
