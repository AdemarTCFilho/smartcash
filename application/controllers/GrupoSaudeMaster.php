<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class GrupoSaudeMaster extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('grupoSaudeMaster_model');
        $this->data['menuGrupoSaudeMaster'] = 'Grupo Saúde Master';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vGrupoSaudeMaster')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Grupo Saúde Master.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/grupoSaudeMaster/gerenciar/';

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'grupoSaudeMaster/grupoSaudeMaster';
        return $this->layout();
    }

    public function getDados()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vGrupoSaudeMaster')) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Sem permissão']);
            exit;
        }

        $periodo = $this->input->get('periodo') ?: 'mensal';
        $mesSelecionado = $this->input->get('mes');
        $idEmpresa = $this->input->get('idEmpresa');

        $dataFim = date('Y-m-d');

        if ($periodo === 'anual') {
            $dataInicio = date('Y-m-d', strtotime('-12 months'));
        } elseif ($periodo === 'trimestral') {
            $dataInicio = date('Y-m-d', strtotime('-3 months'));
        } else {
            if ($mesSelecionado) {
                $dataInicio = $mesSelecionado . '-01';
                $dataFim = date('Y-m-t', strtotime($dataInicio));
            } else {
                $dataInicio = date('Y-m-01');
            }
        }

        $infos = $this->grupoSaudeMaster_model->getInfosGrupo();
        $receita = $this->grupoSaudeMaster_model->getReceitaConsolidada($dataInicio, $dataFim, $idEmpresa);
        $despesa = $this->grupoSaudeMaster_model->getDespesaConsolidada($dataInicio, $dataFim, $idEmpresa);
        $lucro = (float)$receita - (float)$despesa;
        $margem = (float)$receita > 0 ? round(($lucro / (float)$receita) * 100, 1) : 0;

        $evolucao = $this->grupoSaudeMaster_model->getEvolucaoMensal(12, $idEmpresa);
        $empresasRD = $this->grupoSaudeMaster_model->getReceitaDespesaPorEmpresa($dataInicio, $dataFim, $idEmpresa);
        $mix = $this->grupoSaudeMaster_model->getMixReceitaPorEmpresa($dataInicio, $dataFim, $idEmpresa);
        $indicadores = $this->grupoSaudeMaster_model->getIndicadoresPorEmpresa($dataInicio, $dataFim, $idEmpresa);
        $topUnidades = $this->grupoSaudeMaster_model->getTopUnidadesPorLucro($dataInicio, $dataFim, 5, $idEmpresa);

        $receitaTotal = (float)$receita;
        $labelsMix = [];
        $dataMix = [];
        $colorsMix = ['#6172FF', '#F4C542', '#FF5B6A', '#42D67A', '#A855F7', '#22D3EE', '#FB923C', '#34D399'];
        foreach ($mix as $m) {
            $labelsMix[] = $m->nomeEmpresa;
            $pct = $receitaTotal > 0 ? round(((float)$m->receita / $receitaTotal) * 100, 1) : 0;
            $dataMix[] = $pct;
        }

        $labelsEmpresas = [];
        $receitasEmp = [];
        $despesasEmp = [];
        foreach ($empresasRD as $e) {
            $labelsEmpresas[] = $e->nomeEmpresa;
            $receitasEmp[] = (float)$e->receita;
            $despesasEmp[] = (float)$e->despesa;
        }

        $mesLabel = $mesSelecionado ? date('m/Y', strtotime($mesSelecionado . '-01')) : date('m/Y');

        header('Content-Type: application/json');
        try {
            echo json_encode([
                'infos' => $infos,
                'receita' => $receita,
                'despesa' => $despesa,
                'lucro' => $lucro,
                'margem' => $margem,
                'mesLabel' => $mesLabel,
                'evolucao' => $evolucao,
                'empresasRD' => [
                    'labels' => $labelsEmpresas,
                    'receitas' => $receitasEmp,
                    'despesas' => $despesasEmp,
                ],
                'mix' => [
                    'labels' => $labelsMix,
                    'data' => $dataMix,
                    'colors' => array_slice($colorsMix, 0, count($labelsMix)),
                ],
                'indicadores' => $indicadores,
                'topUnidades' => $topUnidades,
            ], JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Erro ao gerar JSON: ' . $e->getMessage()]);
        }
        exit;
    }

    public function exportarCSV()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vGrupoSaudeMaster')) {
            redirect(base_url());
        }

        $periodo = $this->input->get('periodo') ?: 'mensal';
        $dataFim = date('Y-m-d');

        if ($periodo === 'anual') {
            $dataInicio = date('Y-m-d', strtotime('-12 months'));
        } elseif ($periodo === 'trimestral') {
            $dataInicio = date('Y-m-d', strtotime('-3 months'));
        } else {
            $dataInicio = date('Y-m-01');
        }

        $infos = $this->grupoSaudeMaster_model->getInfosGrupo();
        $receita = $this->grupoSaudeMaster_model->getReceitaConsolidada($dataInicio, $dataFim);
        $despesa = $this->grupoSaudeMaster_model->getDespesaConsolidada($dataInicio, $dataFim);
        $indicadores = $this->grupoSaudeMaster_model->getIndicadoresPorEmpresa($dataInicio, $dataFim);
        $topUnidades = $this->grupoSaudeMaster_model->getTopUnidadesPorLucro($dataInicio, $dataFim, 5);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="grupo_saude_master_' . $periodo . '.csv"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, ['Grupo Saúde Master - ' . strtoupper($periodo), '', '', '', '']);
        fputcsv($output, []);
        fputcsv($output, ['Indicador', 'Valor', '', '', '']);
        fputcsv($output, ['Unidades', $infos->unidades, '', '', '']);
        fputcsv($output, ['Empresas', $infos->empresas, '', '', '']);
        fputcsv($output, ['Receita Consolidada', number_format($receita, 2, ',', '.'), '', '', '']);
        fputcsv($output, ['Despesa Consolidada', number_format($despesa, 2, ',', '.'), '', '', '']);
        fputcsv($output, ['Lucro Consolidado', number_format($receita - $despesa, 2, ',', '.'), '', '', '']);
        fputcsv($output, []);
        fputcsv($output, ['Empresa', 'Unidades', 'Receita', 'Despesa', 'Lucro']);
        if ($indicadores) {
            foreach ($indicadores as $e) {
                $l = (float)$e->receita - (float)$e->despesa;
                fputcsv($output, [
                    $e->nomeEmpresa,
                    $e->qtdUnidades,
                    number_format($e->receita, 2, ',', '.'),
                    number_format($e->despesa, 2, ',', '.'),
                    number_format($l, 2, ',', '.'),
                ]);
            }
        }
        fputcsv($output, []);
        fputcsv($output, ['Top Unidades por Lucro', '', '', '', '']);
        if ($topUnidades) {
            foreach ($topUnidades as $i => $u) {
                $l = (float)$u->receita - (float)$u->despesa;
                fputcsv($output, [
                    ($i + 1) . '. ' . $u->nomeUnidade,
                    $u->nomeEmpresa,
                    number_format($u->receita, 2, ',', '.'),
                    number_format($l, 2, ',', '.'),
                    '',
                ]);
            }
        }

        fclose($output);
        exit;
    }

    public function exportarPDF()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vGrupoSaudeMaster')) {
            redirect(base_url());
        }
        redirect(base_url('grupoSaudeMaster/exportarCSV?' . $_SERVER['QUERY_STRING']));
    }
}
