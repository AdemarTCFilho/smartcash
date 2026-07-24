<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class DreGerencial extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('DreGerencial_model');
        $this->data['menuDreGerencial'] = 'DRE Gerencial';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vDreGerencial')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar DRE Gerencial.');
            redirect(base_url());
        }

        $this->data['view'] = 'dreGerencial/dreGerencial';
        return $this->layout();
    }

    public function getDados()
    {
        header('Content-Type: application/json');
        try {
            $mes = $this->input->get('mes') ?: date('Y-m');
            $idUnidade = $this->input->get('idUnidade');
            $idEmpresa = $this->input->get('idEmpresa');

            $receitas = $this->DreGerencial_model->getTotalReceitas($mes, $idUnidade, $idEmpresa);
            $despesas = $this->DreGerencial_model->getTotalDespesas($mes, $idUnidade, $idEmpresa);

            $recVal = (float)$receitas->total;
            $despVal = (float)$despesas->total;
            $resultado = $recVal - $despVal;
            $margem = $recVal > 0 ? round(($resultado / $recVal) * 100, 1) : 0;

            $receitasCat = $this->DreGerencial_model->getReceitasPorCategoria($mes, $idUnidade, $idEmpresa);
            $despesasCat = $this->DreGerencial_model->getDespesasPorCategoria($mes, $idUnidade, $idEmpresa);

            $comparativo = $this->DreGerencial_model->getComparativo($mes, $idUnidade, $idEmpresa);
            $evolucao = $this->DreGerencial_model->getEvolucao($mes, $idUnidade, $idEmpresa);

            echo json_encode([
                'mes' => $mes,
                'receitas' => [
                    'total' => $recVal,
                    'qtd' => (int)$receitas->qtd,
                ],
                'despesas' => [
                    'total' => $despVal,
                    'qtd' => (int)$despesas->qtd,
                ],
                'resultado' => $resultado,
                'margem' => $margem,
                'estrutura' => [
                    'receitas' => $receitasCat,
                    'despesas' => $despesasCat,
                ],
                'comparativo' => $comparativo,
                'evolucao' => $evolucao,
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function listarEmpresas()
    {
        echo json_encode(['data' => $this->DreGerencial_model->getAllEmpresas()]);
    }

    public function listarUnidades()
    {
        $idEmpresa = $this->input->get('idEmpresa');
        echo json_encode(['data' => $this->DreGerencial_model->getUnidadesPorEmpresa($idEmpresa)]);
    }

    public function exportarCSV()
    {
        $mes = $this->input->get('mes') ?: date('Y-m');
        $idUnidade = $this->input->get('idUnidade');
        $idEmpresa = $this->input->get('idEmpresa');

        $receitas = $this->DreGerencial_model->getTotalReceitas($mes, $idUnidade, $idEmpresa);
        $despesas = $this->DreGerencial_model->getTotalDespesas($mes, $idUnidade, $idEmpresa);
        $receitasCat = $this->DreGerencial_model->getReceitasPorCategoria($mes, $idUnidade, $idEmpresa);
        $despesasCat = $this->DreGerencial_model->getDespesasPorCategoria($mes, $idUnidade, $idEmpresa);
        $evolucao = $this->DreGerencial_model->getEvolucao($mes, $idUnidade, $idEmpresa);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="dre_gerencial_' . $mes . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, ['DRE Gerencial - ' . $mes], ';');
        fputcsv($output, ['Categoria', 'Tipo', 'Valor', 'Qtd'], ';');
        fputcsv($output, ['RECEITAS', '', (float)$receitas->total, (int)$receitas->qtd], ';');
        foreach ($receitasCat as $r) {
            fputcsv($output, ['  ' . $r->nomeCategoria, 'Receita', (float)$r->total, (int)$r->qtd], ';');
        }
        fputcsv($output, ['DESPESAS', '', (float)$despesas->total, (int)$despesas->qtd], ';');
        foreach ($despesasCat as $d) {
            fputcsv($output, ['  ' . $d->nomeCategoria, 'Despesa', (float)$d->total, (int)$d->qtd], ';');
        }
        fputcsv($output, ['RESULTADO', '', (float)$receitas->total - (float)$despesas->total, ''], ';');
        fputcsv($output, [], ';');
        fputcsv($output, ['Evolucao - Ultimos 12 meses'], ';');
        fputcsv($output, ['Mes', 'Receitas', 'Despesas', 'Resultado'], ';');
        foreach ($evolucao as $e) {
            fputcsv($output, [$e['mes'], $e['receitas'], $e['despesas'], $e['resultado']], ';');
        }
        fclose($output);
        exit;
    }
}
