<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class RankingReceitasDespesas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('rankingReceitasDespesas_model');
        $this->data['menuRankingReceitasDespesas'] = 'Ranking de Receitas & Despesas';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vRankingReceitasDespesas')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Ranking de Receitas & Despesas.');
            redirect(base_url());
        }

        $this->data['view'] = 'rankingReceitasDespesas/rankingReceitasDespesas';
        return $this->layout();
    }

    public function getDados()
    {
        $tipoPeriodo = $this->input->get('tipoPeriodo') ?: 'mensal';
        $periodo = $this->input->get('periodo');
        $idEmpresa = $this->input->get('idEmpresa');
        $visao = $this->input->get('visao') ?: 'unidade';

        $resumo = $this->rankingReceitasDespesas_model->getResumo($tipoPeriodo, $periodo, $idEmpresa);

        if ($visao === 'empresa') {
            $dados = $this->rankingReceitasDespesas_model->getPorEmpresa($tipoPeriodo, $periodo, $idEmpresa);
        } else {
            $dados = $this->rankingReceitasDespesas_model->getPorUnidade($tipoPeriodo, $periodo, $idEmpresa);
        }

        $rankingReceitas = $this->rankingReceitasDespesas_model->getRankingReceitas($tipoPeriodo, $periodo, $idEmpresa, 5);
        $rankingDespesas = $this->rankingReceitasDespesas_model->getRankingDespesas($tipoPeriodo, $periodo, $idEmpresa, 5);

        echo json_encode([
            'resumo' => $resumo,
            'dados' => $dados,
            'rankingReceitas' => $rankingReceitas,
            'rankingDespesas' => $rankingDespesas,
        ]);
    }

    public function listarEmpresas()
    {
        $empresas = $this->rankingReceitasDespesas_model->getAllEmpresas();
        echo json_encode(['data' => $empresas]);
    }

    public function exportarCSV()
    {
        $tipoPeriodo = $this->input->get('tipoPeriodo') ?: 'mensal';
        $periodo = $this->input->get('periodo');
        $idEmpresa = $this->input->get('idEmpresa');

        $dados = $this->rankingReceitasDespesas_model->getPorUnidade($tipoPeriodo, $periodo, $idEmpresa);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=ranking_receitas_despesas.csv');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['Empresa', 'Unidade', 'Receita', 'Despesa', 'Saldo'], ';');

        foreach ($dados as $d) {
            fputcsv($output, [
                $d['nomeEmpresa'],
                $d['nomeUnidade'],
                number_format($d['receita'], 2, ',', '.'),
                number_format($d['despesa'], 2, ',', '.'),
                number_format($d['saldo'], 2, ',', '.'),
            ], ';');
        }

        fclose($output);
    }
}
