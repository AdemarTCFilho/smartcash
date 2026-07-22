<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ConciliacaoBancaria extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('conciliacaoBancaria_model');
        $this->data['menuConciliacaoBancaria'] = 'Conciliação Bancária';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vConciliacaoBancaria')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Conciliação Bancária.');
            redirect(base_url());
        }
        $this->data['view'] = 'conciliacaoBancaria/conciliacaoBancaria';
        return $this->layout();
    }

    public function getDados()
    {
        $filtros = [
            'idContaBancaria' => $this->input->get('idContaBancaria'),
            'dataInicio' => $this->input->get('dataInicio'),
            'dataFim' => $this->input->get('dataFim'),
            'naoConciliadas' => $this->input->get('naoConciliadas'),
        ];

        $movimentacoes = $this->conciliacaoBancaria_model->getAll($filtros);

        $resumo = null;
        $saldoConta = null;
        if ($filtros['idContaBancaria']) {
            $resumo = $this->conciliacaoBancaria_model->getResumo(
                $filtros['idContaBancaria'],
                $filtros['dataInicio'],
                $filtros['dataFim']
            );
            $saldoConta = $this->conciliacaoBancaria_model->getSaldoConta($filtros['idContaBancaria']);
        }

        echo json_encode([
            'movimentacoes' => $movimentacoes,
            'resumo' => $resumo,
            'saldoConta' => $saldoConta,
        ]);
    }

    public function getContas()
    {
        echo json_encode($this->conciliacaoBancaria_model->getAllContas());
    }

    public function getDadosById()
    {
        $id = $this->input->get('id');
        echo json_encode($this->conciliacaoBancaria_model->getById($id));
    }

    public function getConta()
    {
        $id = $this->input->get('id');
        echo json_encode($this->conciliacaoBancaria_model->getContaById($id));
    }

    public function adicionar()
    {
        $valor = $this->input->post('valor');
        $data = [
            'idUsuarios' => $this->session->userdata('id'),
            'idContaBancaria' => $this->input->post('idContaBancaria'),
            'tipo' => $this->input->post('tipo'),
            'valor' => $this->formatDecimal($valor),
            'descricao' => $this->input->post('descricao'),
            'data' => $this->input->post('data'),
            'conciliada' => 0,
        ];

        if ($this->conciliacaoBancaria_model->add($data)) {
            echo json_encode(['success' => true, 'message' => 'Movimentação lançada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao lançar movimentação.']);
        }
    }

    public function conciliar()
    {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID não informado.']);
            return;
        }

        if ($this->conciliacaoBancaria_model->conciliar($id)) {
            echo json_encode(['success' => true, 'message' => 'Movimentação conciliada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao conciliar movimentação.']);
        }
    }

    public function excluir()
    {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID não informado.']);
            return;
        }

        if ($this->conciliacaoBancaria_model->delete($id)) {
            echo json_encode(['success' => true, 'message' => 'Movimentação excluída com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir movimentação.']);
        }
    }

    public function exportarCSV()
    {
        $filtros = [
            'idContaBancaria' => $this->input->get('idContaBancaria'),
            'dataInicio' => $this->input->get('dataInicio'),
            'dataFim' => $this->input->get('dataFim'),
            'naoConciliadas' => $this->input->get('naoConciliadas'),
        ];

        $dados = $this->conciliacaoBancaria_model->getAll($filtros);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=conciliacao.csv');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['Data', 'Conta', 'Descrição', 'Tipo', 'Valor', 'Conciliada'], ';');

        foreach ($dados as $d) {
            fputcsv($output, [
                $d->data,
                $d->nomeConta,
                $d->descricao,
                $d->tipo,
                number_format($d->valor, 2, ',', '.'),
                $d->conciliada ? 'Sim' : 'Não',
            ], ';');
        }
        fclose($output);
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
