<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class DreImportacao extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('DreImportacao_model');
        $this->data['menuDreImportacao'] = 'DRE Importação';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vDreGerencial')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar DRE Importação.');
            redirect(base_url());
        }

        $this->data['view'] = 'dreImportacao/dreImportacao';
        return $this->layout();
    }

    public function importarPlanilha()
    {
        header('Content-Type: application/json');

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aDreGerencial')) {
            echo json_encode(['success' => false, 'message' => 'Sem permissão para importar.']);
            exit;
        }

        if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado.']);
            exit;
        }

        $arquivo = $_FILES['arquivo'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

        $extensoesPermitidas = ['csv', 'xls', 'xlsx'];
        if (!in_array($extensao, $extensoesPermitidas)) {
            echo json_encode(['success' => false, 'message' => 'Formato não permitido. Use CSV, XLS ou XLSX.']);
            exit;
        }

        $registros = [];

        try {
            if ($extensao === 'csv') {
                $registros = $this->parseCSV($arquivo['tmp_name']);
            } else {
                $registros = $this->parseExcel($arquivo['tmp_name']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao ler arquivo: ' . $e->getMessage()]);
            exit;
        }

        if (empty($registros)) {
            echo json_encode(['success' => false, 'message' => 'Nenhum registro válido encontrado na planilha.']);
            exit;
        }

        $idUser = $this->session->userdata('id');
        foreach ($registros as &$reg) {
            $reg['idUsuario'] = $idUser;
        }
        unset($reg);

        $this->DreImportacao_model->inserirLote($registros);

        $creditos = 0;
        $debitos = 0;
        foreach ($registros as $reg) {
            if ($reg['status'] === 'C') $creditos++;
            elseif ($reg['status'] === 'D') $debitos++;
        }

        echo json_encode([
            'success' => true,
            'message' => count($registros) . ' registros importados com sucesso!',
            'total' => count($registros),
            'creditos' => $creditos,
            'debitos' => $debitos,
        ]);
        exit;
    }

    private function parseCSV($filePath)
    {
        $registros = [];
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new Exception('Não foi possível abrir o arquivo CSV.');
        }

        $cabecalho = fgetcsv($handle, 0, ';');
        if (!$cabecalho) {
            throw new Exception('Arquivo CSV vazio ou inválido.');
        }

        $cabecalho = array_map('trim', $cabecalho);
        $cabecalho = array_map(function ($v) {
            return mb_strtoupper($v, 'UTF-8');
        }, $cabecalho);

        while (($linha = fgetcsv($handle, 0, ';')) !== false) {
            if (count($linha) < count($cabecalho)) {
                continue;
            }

            $row = array_combine($cabecalho, array_map('trim', $linha));

            $data = $this->parseDate($row['DATA'] ?? '');
            $dataBal = $this->parseDate($row['DATA_BALANCETE'] ?? '');
            $valor = $this->parseValor($row['VALOR'] ?? '0');
            $status = strtoupper(trim($row['STATUS'] ?? ''));

            if (!in_array($status, ['C', 'D'])) {
                continue;
            }

            $registros[] = [
                'data' => $data ?: null,
                'dataBalancete' => $dataBal ?: null,
                'historico' => $row['HISTORICO'] ?? '',
                'valor' => $valor,
                'status' => $status,
                'detalhamentoHist' => $row['DETALHAMENTO_HIST'] ?? '',
                'despesas' => $row['DESPESAS'] ?? '',
                'centroCusto' => $row['CENTRO DE CUSTO'] ?? '',
                'idCategoria' => null,
                'idSubCategoria' => null,
                'idUnidade' => null,
            ];
        }

        fclose($handle);
        return $registros;
    }

    private function parseExcel($filePath)
    {
        $registros = [];
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        if (count($rows) < 2) {
            throw new Exception('Planilha vazia ou sem dados.');
        }

        $cabecalho = array_map(function ($v) {
            return mb_strtoupper(trim($v), 'UTF-8');
        }, $rows[0]);

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (count($row) < count($cabecalho)) {
                continue;
            }

            $rowData = array_combine($cabecalho, array_map('trim', $row));

            $data = $this->parseDate($rowData['DATA'] ?? '');
            $dataBal = $this->parseDate($rowData['DATA_BALANCETE'] ?? '');
            $valor = $this->parseValor($rowData['VALOR'] ?? '0');
            $status = strtoupper(trim($rowData['STATUS'] ?? ''));

            if (!in_array($status, ['C', 'D'])) {
                continue;
            }

            $registros[] = [
                'data' => $data ?: null,
                'dataBalancete' => $dataBal ?: null,
                'historico' => $rowData['HISTORICO'] ?? '',
                'valor' => $valor,
                'status' => $status,
                'detalhamentoHist' => $rowData['DETALHAMENTO_HIST'] ?? '',
                'despesas' => $rowData['DESPESAS'] ?? '',
                'centroCusto' => $rowData['CENTRO DE CUSTO'] ?? '',
                'idCategoria' => null,
                'idSubCategoria' => null,
                'idUnidade' => null,
            ];
        }

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $registros;
    }

    private function parseDate($dateStr)
    {
        $dateStr = trim($dateStr);
        if (empty($dateStr)) {
            return null;
        }

        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dateStr, $m)) {
            return "{$m[3]}-{$m[2]}-{$m[1]}";
        }

        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $dateStr)) {
            return $dateStr;
        }

        return null;
    }

    private function parseValor($valorStr)
    {
        $valorStr = trim($valorStr);
        $valorStr = str_replace(['.', ','], ['', '.'], $valorStr);
        $valor = (float) $valorStr;
        return $valor;
    }

    public function listarRegistros()
    {
        header('Content-Type: application/json');
        $status = $this->input->get('status');

        if (!in_array($status, ['C', 'D'])) {
            echo json_encode(['success' => false, 'message' => 'Status inválido.']);
            exit;
        }

        $registros = $this->DreImportacao_model->listarPorStatus($status);
        echo json_encode(['success' => true, 'data' => $registros]);
        exit;
    }

    public function contarRegistros()
    {
        header('Content-Type: application/json');
        $creditos = $this->DreImportacao_model->contarPorStatus('C');
        $debitos = $this->DreImportacao_model->contarPorStatus('D');
        echo json_encode(['success' => true, 'creditos' => $creditos, 'debitos' => $debitos]);
        exit;
    }

    public function salvarMapeamento()
    {
        header('Content-Type: application/json');

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eDreGerencial')) {
            echo json_encode(['success' => false, 'message' => 'Sem permissão para editar.']);
            exit;
        }

        $id = $this->input->post('idImportacao');
        $idCategoria = $this->input->post('idCategoria');
        $idSubCategoria = $this->input->post('idSubCategoria');
        $idUnidade = $this->input->post('idUnidade');

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID inválido.']);
            exit;
        }

        $result = $this->DreImportacao_model->atualizarMapeamento(
            $id,
            $idCategoria,
            $idSubCategoria,
            $idUnidade
        );

        echo json_encode(['success' => $result !== false, 'message' => $result ? 'Salvo com sucesso!' : 'Erro ao salvar.']);
        exit;
    }

    public function excluirImportacao()
    {
        header('Content-Type: application/json');

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dDreGerencial')) {
            echo json_encode(['success' => false, 'message' => 'Sem permissão para excluir.']);
            exit;
        }

        $result = $this->DreImportacao_model->excluirTodos();
        echo json_encode(['success' => true, 'message' => 'Registros excluídos com sucesso!']);
        exit;
    }

    public function listarCategorias()
    {
        header('Content-Type: application/json');
        $busca = $this->input->get('q');
        $tipo = $this->input->get('tipo');
        $categorias = $this->DreImportacao_model->getCategorias($busca, $tipo);

        $results = [];
        foreach ($categorias as $c) {
            $results[] = [
                'id' => $c->idSubCategoria,
                'text' => $c->nomeSubCategoria . ' (' . $c->nomeCategoria . ')',
                'idCategoria' => $c->idCategoria,
            ];
        }

        echo json_encode(['results' => $results]);
        exit;
    }

    public function listarUnidades()
    {
        header('Content-Type: application/json');
        $busca = $this->input->get('q');
        $unidades = $this->DreImportacao_model->getUnidades($busca);

        $results = [];
        foreach ($unidades as $u) {
            $results[] = [
                'id' => $u->idUnidade,
                'text' => $u->nomeUnidade,
            ];
        }

        echo json_encode(['results' => $results]);
        exit;
    }
}
