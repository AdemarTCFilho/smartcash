<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class FluxoCaixa extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('fluxoCaixa_model');
        $this->data['menuFluxoCaixa'] = 'Fluxo de Caixa';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vFluxoCaixa')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Fluxo de Caixa.');
            redirect(base_url());
        }
        $this->data['view'] = 'fluxoCaixa/fluxoCaixa';
        return $this->layout();
    }

    private function getFiltros()
    {
        return [
            'dataInicio' => $this->input->get('dataInicio'),
            'dataFim' => $this->input->get('dataFim'),
            'idUnidade' => $this->input->get('idUnidade'),
            'idContaBancaria' => $this->input->get('idContaBancaria'),
        ];
    }

    public function getDados()
    {
        $f = $this->getFiltros();
        $di = $f['dataInicio'];
        $df = $f['dataFim'];
        $idUni = $f['idUnidade'];
        $idConta = $f['idContaBancaria'];

        $saldoBancario = $this->fluxoCaixa_model->getSaldoBancarioAtual($idConta);
        $entradas = $this->fluxoCaixa_model->getEntradasRealizadas($di, $df, $idUni, $idConta);
        $saidas = $this->fluxoCaixa_model->getSaidasRealizadas($di, $df, $idUni, $idConta);
        $aReceber = $this->fluxoCaixa_model->getAReceberPrevisto($idUni);
        $aPagar = $this->fluxoCaixa_model->getAPagarPrevisto($idUni);
        $inadimplencia = $this->fluxoCaixa_model->getInadimplencia($idUni);
        $contasVencidas = $this->fluxoCaixa_model->getContasVencidas($idUni);

        $entVal = (float)$entradas->total;
        $saiVal = (float)$saidas->total;
        $resultado = $entVal - $saiVal;

        echo json_encode([
            'saldoBancario' => $saldoBancario->total,
            'entradas' => $entradas,
            'saidas' => $saidas,
            'resultado' => $resultado,
            'aReceber' => $aReceber,
            'aPagar' => $aPagar,
            'inadimplencia' => $inadimplencia,
            'contasVencidas' => $contasVencidas,
        ]);
    }

    public function getFluxoDiario()
    {
        $f = $this->getFiltros();
        $di = $f['dataInicio'];
        $df = $f['dataFim'];
        if (!$di) $di = date('Y-m-01');
        if (!$df) $df = date('Y-m-t');

        echo json_encode(
            $this->fluxoCaixa_model->getFluxoDiario($di, $df, $f['idUnidade'])
        );
    }

    public function getDadosGraficos()
    {
        $f = $this->getFiltros();
        $di = $f['dataInicio'];
        $df = $f['dataFim'];
        if (!$di) $di = date('Y-m-01');
        if (!$df) $df = date('Y-m-t');

        $entradas = $this->fluxoCaixa_model->getEntradasRealizadas($di, $df, $f['idUnidade'], $f['idContaBancaria']);
        $saidas = $this->fluxoCaixa_model->getSaidasRealizadas($di, $df, $f['idUnidade'], $f['idContaBancaria']);

        $fluxo = $this->fluxoCaixa_model->getFluxoDiario($di, $df, $f['idUnidade']);

        $dias = [];
        $valsEnt = [];
        $valsSai = [];
        $saldoAcum = [];

        foreach ($fluxo as $d) {
            $dias[] = date('d/m', strtotime($d['data']));
            $valsEnt[] = $d['entradas'];
            $valsSai[] = $d['saidas'];
            $saldoAcum[] = $d['saldo'];
        }

        echo json_encode([
            'entradasTotal' => $entradas->total,
            'saidasTotal' => $saidas->total,
            'dias' => $dias,
            'entradas' => $valsEnt,
            'saidas' => $valsSai,
            'saldoAcumulado' => $saldoAcum,
        ]);
    }

    public function getUnidades()
    {
        echo json_encode($this->fluxoCaixa_model->getAllUnidades());
    }

    public function getContasBancarias()
    {
        echo json_encode($this->fluxoCaixa_model->getAllContasBancarias());
    }

    public function exportarCSV()
    {
        $f = $this->getFiltros();
        $di = $f['dataInicio'];
        $df = $f['dataFim'];
        if (!$di) $di = date('Y-m-01');
        if (!$df) $df = date('Y-m-t');

        $fluxo = $this->fluxoCaixa_model->getFluxoDiario($di, $df, $f['idUnidade']);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=fluxo_caixa.csv');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['Data', 'Entradas', 'Saídas', 'Saldo Acumulado'], ';');

        foreach ($fluxo as $d) {
            fputcsv($output, [
                $d['data'],
                number_format($d['entradas'], 2, ',', '.'),
                number_format($d['saidas'], 2, ',', '.'),
                number_format($d['saldo'], 2, ',', '.'),
            ], ';');
        }
        fclose($output);
    }
}
