<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class BaixasFinanceiras extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('baixasFinanceiras_model');
        $this->data['menuBaixasFinanceiras'] = 'Baixas Financeiras';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vBaixasFinanceiras')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Baixas Financeiras.');
            redirect(base_url());
        }
        $this->data['view'] = 'baixasFinanceiras/baixasFinanceiras';
        return $this->layout();
    }

    public function getDados()
    {
        $tipoPeriodo = $this->input->get('tipoPeriodo') ?: null;
        $periodo = $this->input->get('periodo') ?: null;
        $idEmpresa = $this->input->get('idEmpresa') ?: null;
        $busca = $this->input->get('busca') ?: null;

        $resumo = $this->baixasFinanceiras_model->getResumo($tipoPeriodo, $periodo, $idEmpresa);
        $aPagar = $this->baixasFinanceiras_model->getContasPagarPendentes($tipoPeriodo, $periodo, $idEmpresa, $busca);
        $aReceber = $this->baixasFinanceiras_model->getContasReceberPendentes($tipoPeriodo, $periodo, $idEmpresa, $busca);
        $histPag = $this->baixasFinanceiras_model->getHistoricoPagamentos($tipoPeriodo, $periodo, $idEmpresa);
        $histRec = $this->baixasFinanceiras_model->getHistoricoRecebimentos($tipoPeriodo, $periodo, $idEmpresa);

        echo json_encode([
            'resumo' => $resumo,
            'aPagar' => $aPagar,
            'aReceber' => $aReceber,
            'histPag' => $histPag,
            'histRec' => $histRec,
        ]);
    }

    public function listarEmpresas()
    {
        $empresas = $this->baixasFinanceiras_model->getAllEmpresas();
        echo json_encode(['data' => $empresas]);
    }

    public function baixarPagar()
    {
        $id = $this->input->post('id');
        $valorPago = $this->input->post('valorPago');

        if (!$id || !$valorPago) {
            echo json_encode(['success' => false, 'message' => 'ID e valor são obrigatórios.']);
            return;
        }

        $conta = $this->baixasFinanceiras_model->get('contas_pagar', '*', "idContaPagar = $id", 1, 0, true);
        if (!$conta) {
            echo json_encode(['success' => false, 'message' => 'Conta não encontrada.']);
            return;
        }

        if ($conta->status === 'liquidado') {
            echo json_encode(['success' => false, 'message' => 'Esta conta já foi liquidada.']);
            return;
        }

        $data = [
            'status' => 'liquidado',
            'valorPago' => $this->formatDecimal($valorPago),
            'dataPagamento' => date('Y-m-d'),
        ];

        $this->baixasFinanceiras_model->edit('contas_pagar', $data, 'idContaPagar', $id);
        echo json_encode(['success' => true, 'message' => 'Pagamento registrado com sucesso!']);
    }

    public function baixarReceber()
    {
        $id = $this->input->post('id');
        $valorRecebido = $this->input->post('valorRecebido');

        if (!$id || !$valorRecebido) {
            echo json_encode(['success' => false, 'message' => 'ID e valor são obrigatórios.']);
            return;
        }

        $conta = $this->baixasFinanceiras_model->get('contas_receber', '*', "idContaReceber = $id", 1, 0, true);
        if (!$conta) {
            echo json_encode(['success' => false, 'message' => 'Conta não encontrada.']);
            return;
        }

        if ($conta->status === 'liquidado') {
            echo json_encode(['success' => false, 'message' => 'Esta conta já foi liquidada.']);
            return;
        }

        $data = [
            'status' => 'liquidado',
            'valorRecebido' => $this->formatDecimal($valorRecebido),
            'dataRecebimento' => date('Y-m-d'),
        ];

        $this->baixasFinanceiras_model->edit('contas_receber', $data, 'idContaReceber', $id);
        echo json_encode(['success' => true, 'message' => 'Recebimento registrado com sucesso!']);
    }

    public function exportarCSV()
    {
        $tipoPeriodo = $this->input->get('tipoPeriodo') ?: null;
        $periodo = $this->input->get('periodo') ?: null;
        $idEmpresa = $this->input->get('idEmpresa') ?: null;
        $aba = $this->input->get('aba') ?: 'pagar';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=baixas_' . $aba . '.csv');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        switch ($aba) {
            case 'pagar':
                fputcsv($output, ['Fornecedor', 'Vencimento', 'Valor', 'Valor Pago', 'Saldo', 'Status'], ';');
                $dados = $this->baixasFinanceiras_model->getContasPagarPendentes($tipoPeriodo, $periodo, $idEmpresa);
                foreach ($dados as $d) {
                    $saldo = $d->valor - ($d->valorPago ?? 0);
                    fputcsv($output, [
                        $d->fornecedor ?? '-',
                        $d->vencimento,
                        number_format($d->valor, 2, ',', '.'),
                        number_format($d->valorPago ?? 0, 2, ',', '.'),
                        number_format($saldo, 2, ',', '.'),
                        $d->status,
                    ], ';');
                }
                break;
            case 'receber':
                fputcsv($output, ['Cliente', 'Vencimento', 'Valor', 'Valor Recebido', 'Saldo', 'Status'], ';');
                $dados = $this->baixasFinanceiras_model->getContasReceberPendentes($tipoPeriodo, $periodo, $idEmpresa);
                foreach ($dados as $d) {
                    $saldo = $d->valor - ($d->valorRecebido ?? 0);
                    fputcsv($output, [
                        $d->nomeCliente ?? '-',
                        $d->vencimento,
                        number_format($d->valor, 2, ',', '.'),
                        number_format($d->valorRecebido ?? 0, 2, ',', '.'),
                        number_format($saldo, 2, ',', '.'),
                        $d->status,
                    ], ';');
                }
                break;
            case 'hist-pag':
                fputcsv($output, ['Fornecedor', 'Vencimento', 'Valor', 'Valor Pago', 'Data Pagamento'], ';');
                $dados = $this->baixasFinanceiras_model->getHistoricoPagamentos($tipoPeriodo, $periodo, $idEmpresa);
                foreach ($dados as $d) {
                    fputcsv($output, [
                        $d->fornecedor ?? '-',
                        $d->vencimento,
                        number_format($d->valor, 2, ',', '.'),
                        number_format($d->valorPago ?? 0, 2, ',', '.'),
                        $d->dataPagamento ?? '-',
                    ], ';');
                }
                break;
            case 'hist-rec':
                fputcsv($output, ['Cliente', 'Vencimento', 'Valor', 'Valor Recebido', 'Data Recebimento'], ';');
                $dados = $this->baixasFinanceiras_model->getHistoricoRecebimentos($tipoPeriodo, $periodo, $idEmpresa);
                foreach ($dados as $d) {
                    fputcsv($output, [
                        $d->nomeCliente ?? '-',
                        $d->vencimento,
                        number_format($d->valor, 2, ',', '.'),
                        number_format($d->valorRecebido ?? 0, 2, ',', '.'),
                        $d->dataRecebimento ?? '-',
                    ], ';');
                }
                break;
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
