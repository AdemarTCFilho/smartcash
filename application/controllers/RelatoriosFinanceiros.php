<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class RelatoriosFinanceiros extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('relatoriosFinanceiros_model');
        $this->data['menuRelatoriosFinanceiros'] = 'Relatórios Financeiros';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vRelatoriosFinanceiros')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Relatórios Financeiros.');
            redirect(base_url());
        }
        $this->data['view'] = 'relatoriosFinanceiros/relatoriosFinanceiros';
        return $this->layout();
    }

    private function getFiltros()
    {
        return [
            'dataInicio' => $this->input->get('dataInicio'),
            'dataFim' => $this->input->get('dataFim'),
            'idUnidade' => $this->input->get('idUnidade'),
        ];
    }

    private function getFiltrosPost()
    {
        return [
            'dataInicio' => $this->input->post('dataInicio'),
            'dataFim' => $this->input->post('dataFim'),
            'idUnidade' => $this->input->post('idUnidade'),
        ];
    }

    public function getDados()
    {
        $f = $this->getFiltros();
        $di = $f['dataInicio'];
        $df = $f['dataFim'];
        $idUni = $f['idUnidade'];

        $recebido = $this->relatoriosFinanceiros_model->getTotalRecebido($di, $df, $idUni);
        $pago = $this->relatoriosFinanceiros_model->getTotalPago($di, $df, $idUni);
        $aReceber = $this->relatoriosFinanceiros_model->getTotalAReceber($idUni);
        $aPagar = $this->relatoriosFinanceiros_model->getTotalAPagar($idUni);
        $inadimplencia = $this->relatoriosFinanceiros_model->getInadimplencia($di, $df, $idUni);
        $pagarVencidas = $this->relatoriosFinanceiros_model->getPagarVencidas($di, $df, $idUni);

        $rVal = (float)$recebido->total;
        $pVal = (float)$pago->total;
        $resultado = $rVal - $pVal;
        $margem = $rVal > 0 ? round(($resultado / $rVal) * 100, 1) : 0;

        echo json_encode([
            'recebido' => $recebido,
            'pago' => $pago,
            'resultado' => $resultado,
            'margem' => $margem,
            'aReceber' => $aReceber,
            'aPagar' => $aPagar,
            'inadimplencia' => $inadimplencia,
            'pagarVencidas' => $pagarVencidas,
            'saldoPeriodo' => $resultado,
        ]);
    }

    public function getInadimplencia()
    {
        $f = $this->getFiltros();
        echo json_encode(
            $this->relatoriosFinanceiros_model->getListaInadimplencia($f['dataInicio'], $f['dataFim'], $f['idUnidade'])
        );
    }

    public function getContasReceber()
    {
        $f = $this->getFiltros();
        echo json_encode(
            $this->relatoriosFinanceiros_model->getContasReceber('pendente', $f['dataInicio'], $f['dataFim'], $f['idUnidade'])
        );
    }

    public function getContasPagar()
    {
        $f = $this->getFiltros();
        echo json_encode(
            $this->relatoriosFinanceiros_model->getContasPagar('pendente', $f['dataInicio'], $f['dataFim'], $f['idUnidade'])
        );
    }

    public function getHistoricoPagamentos()
    {
        $f = $this->getFiltros();
        echo json_encode(
            $this->relatoriosFinanceiros_model->getHistoricoPagamentos($f['dataInicio'], $f['dataFim'], $f['idUnidade'])
        );
    }

    public function getHistoricoRecebimentos()
    {
        $f = $this->getFiltros();
        echo json_encode(
            $this->relatoriosFinanceiros_model->getHistoricoRecebimentos($f['dataInicio'], $f['dataFim'], $f['idUnidade'])
        );
    }

    public function getUnidades()
    {
        echo json_encode($this->relatoriosFinanceiros_model->getAllUnidades());
    }

    public function getPorUnidade()
    {
        $f = $this->getFiltros();
        echo json_encode(
            $this->relatoriosFinanceiros_model->getPorUnidade($f['dataInicio'], $f['dataFim'], $f['idUnidade'])
        );
    }

    public function getReceitasPorCategoria()
    {
        $f = $this->getFiltros();
        echo json_encode(
            $this->relatoriosFinanceiros_model->getReceitasPorCategoria($f['dataInicio'], $f['dataFim'], $f['idUnidade'])
        );
    }

    public function getDespesasPorCategoria()
    {
        $f = $this->getFiltros();
        echo json_encode(
            $this->relatoriosFinanceiros_model->getDespesasPorCategoria($f['dataInicio'], $f['dataFim'], $f['idUnidade'])
        );
    }

    public function getTopClientes()
    {
        $f = $this->getFiltros();
        echo json_encode(
            $this->relatoriosFinanceiros_model->getTopClientes($f['dataInicio'], $f['dataFim'], $f['idUnidade'])
        );
    }

    public function getTopFornecedores()
    {
        $f = $this->getFiltros();
        echo json_encode(
            $this->relatoriosFinanceiros_model->getTopFornecedores($f['dataInicio'], $f['dataFim'], $f['idUnidade'])
        );
    }

    public function exportarCSV()
    {
        $aba = $this->input->get('aba');
        $f = $this->getFiltros();
        $di = $f['dataInicio'];
        $df = $f['dataFim'];
        $idUni = $f['idUnidade'];

        $dados = [];
        $headers = [];
        $filename = 'relatorio.csv';

        switch ($aba) {
            case 'inadimplencia':
                $headers = ['Cliente', 'Vencimento', 'Dias Atraso', 'Valor'];
                $rows = $this->relatoriosFinanceiros_model->getListaInadimplencia($di, $df, $idUni);
                $hoje = new DateTime();
                foreach ($rows as $r) {
                    $venc = new DateTime($r->vencimento);
                    $r->diasAtraso = $hoje > $venc ? $hoje->diff($venc)->days : 0;
                }
                $dados = $rows;
                $filename = 'inadimplencia.csv';
                break;
            case 'contas_receber':
                $headers = ['Cliente', 'Vencimento', 'Valor', 'Status'];
                $dados = $this->relatoriosFinanceiros_model->getContasReceber('pendente', $di, $df, $idUni);
                $filename = 'contas_receber.csv';
                break;
            case 'contas_pagar':
                $headers = ['Cliente', 'Vencimento', 'Valor', 'Status'];
                $dados = $this->relatoriosFinanceiros_model->getContasPagar('pendente', $di, $df, $idUni);
                $filename = 'contas_pagar.csv';
                break;
            case 'historico_pagamentos':
                $headers = ['Cliente', 'Vencimento', 'Valor Pago', 'Data Pagamento'];
                $dados = $this->relatoriosFinanceiros_model->getHistoricoPagamentos($di, $df, $idUni);
                $filename = 'historico_pagamentos.csv';
                break;
            case 'historico_recebimentos':
                $headers = ['Cliente', 'Vencimento', 'Valor Recebido', 'Data Recebimento'];
                $dados = $this->relatoriosFinanceiros_model->getHistoricoRecebimentos($di, $df, $idUni);
                $filename = 'historico_recebimentos.csv';
                break;
            default:
                $headers = ['Indicador', 'Valor'];
                $r = $this->relatoriosFinanceiros_model->getTotalRecebido($di, $df, $idUni);
                $p = $this->relatoriosFinanceiros_model->getTotalPago($di, $df, $idUni);
                $dados = [
                    (object)['indicador' => 'Recebido', 'valor' => $r->total],
                    (object)['indicador' => 'Pago', 'valor' => $p->total],
                ];
                $filename = 'indicadores.csv';
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, $headers, ';');

        foreach ($dados as $d) {
            $row = [];
            foreach ($headers as $h) {
                $field = $this->campoParaHeader($h);
                $row[] = $d->$field ?? '';
            }
            fputcsv($output, $row, ';');
        }
        fclose($output);
    }

    private function campoParaHeader($header)
    {
        $map = [
            'Cliente' => 'nomeCliente',
            'Vencimento' => 'vencimento',
            'Dias Atraso' => 'diasAtraso',
            'Valor' => 'valor',
            'Status' => 'status',
            'Valor Pago' => 'valorPago',
            'Data Pagamento' => 'dataPagamento',
            'Valor Recebido' => 'valorRecebido',
            'Data Recebimento' => 'dataRecebimento',
            'Indicador' => 'indicador',
        ];
        return $map[$header] ?? strtolower(str_replace(' ', '_', $header));
    }
}
