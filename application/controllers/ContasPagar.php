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

        $base = [
            'idClientes' => $idClientes,
            'idUsuarios' => $this->session->userdata('id'),
            'idEmpresa' => $idEmpresa,
            'idUnidade' => $idUnidade ?: null,
            'idSubUnidade' => $idSubUnidade ?: null,
            'unidade' => $this->input->post('unidade'),
            'idCategoria' => $this->input->post('idCategoria') ?: null,
            'idSubCategoria' => $this->input->post('idSubCategoria') ?: null,
            'status' => 'pendente',
        ];

        $observacoes = $this->input->post('observacoes');
        $competencia = $this->input->post('competencia');

        if ($id) {
            $data = $base + [
                'valor' => $this->formatDecimal($valor),
                'vencimento' => $vencimento,
                'competencia' => $competencia ?: null,
                'observacoes' => $observacoes,
            ];
            $this->contasPagar_model->edit('contas_pagar', $data, 'idContaPagar', $id);
            echo json_encode(['success' => true, 'message' => 'Conta atualizada com sucesso!']);
            return;
        }

        $repetir = $this->input->post('repetir');
        $parcelar = $this->input->post('parcelar');

        if ($repetir) {
            $tipoRepeticao = $this->input->post('tipoRepeticao');
            $valorFormatado = $this->formatDecimal($valor);
            $grupo = bin2hex(random_bytes(8));

            if ($tipoRepeticao === 'especifica') {
                $qtd = max(1, (int) $this->input->post('qtdRepeticoes'));

                for ($i = 0; $i < $qtd; $i++) {
                    $data = $base + [
                        'valor' => $valorFormatado,
                        'vencimento' => $this->addMonthsClamped($vencimento, $i)->format('Y-m-d'),
                        'competencia' => $this->addMonthsToCompetencia($competencia, $i),
                        'observacoes' => $observacoes,
                        'recorrente' => 1,
                        'recorrenteIndex' => $i + 1,
                        'recorrenteTotal' => $qtd,
                        'tipoRepeticao' => 'especifica',
                        'grupoLancamento' => $grupo,
                    ];
                    $this->contasPagar_model->add('contas_pagar', $data);
                }

                echo json_encode(['success' => true, 'message' => "$qtd lançamentos recorrentes cadastrados com sucesso!"]);
                return;
            }

            $data = $base + [
                'valor' => $valorFormatado,
                'vencimento' => $vencimento,
                'competencia' => $competencia ?: null,
                'observacoes' => $observacoes,
                'recorrente' => 1,
                'recorrenteIndex' => 1,
                'recorrenteTotal' => null,
                'tipoRepeticao' => 'indeterminado',
                'grupoLancamento' => $grupo,
            ];
            $this->contasPagar_model->add('contas_pagar', $data);

            echo json_encode(['success' => true, 'message' => 'Lançamento recorrente cadastrado com sucesso! Uma nova conta será gerada automaticamente todo mês, até que a recorrência seja cancelada.']);
            return;
        }

        if ($parcelar) {
            $valorTotal = $this->formatDecimal($valor);
            $entrada = $this->formatDecimal($this->input->post('entradaParcelamento'));
            $qtdParcelas = max(1, (int) $this->input->post('qtdParcelas'));

            if ($entrada >= $valorTotal) {
                echo json_encode(['success' => false, 'message' => 'O valor da entrada não pode ser maior ou igual ao valor total.']);
                return;
            }

            $parcelasPost = json_decode($this->input->post('parcelas'), true);
            $parcelas = [];
            if (is_array($parcelasPost) && count($parcelasPost) > 0) {
                foreach ($parcelasPost as $p) {
                    $parcelas[] = [
                        'valor' => $this->formatDecimal($p['valor']),
                        'vencimento' => $p['vencimento'],
                    ];
                }
            } else {
                $parcelas = $this->gerarParcelas($valorTotal, $entrada, $qtdParcelas, $vencimento);
            }

            $grupo = bin2hex(random_bytes(8));
            $totalGerados = 0;

            if ($entrada > 0) {
                $this->contasPagar_model->add('contas_pagar', $base + [
                    'valor' => $entrada,
                    'vencimento' => $vencimento,
                    'competencia' => $competencia ?: null,
                    'observacoes' => trim($observacoes . ' (Entrada)'),
                    'status' => 'liquidado',
                    'valorPago' => $entrada,
                    'dataPagamento' => $vencimento,
                    'grupoLancamento' => $grupo,
                ]);
                $totalGerados++;
            }

            $totalParcelas = count($parcelas);
            foreach ($parcelas as $index => $p) {
                $this->contasPagar_model->add('contas_pagar', $base + [
                    'valor' => $p['valor'],
                    'vencimento' => $p['vencimento'],
                    'competencia' => $this->addMonthsToCompetencia($competencia, $index),
                    'observacoes' => trim($observacoes . " (Parcela " . ($index + 1) . "/$totalParcelas)"),
                    'grupoLancamento' => $grupo,
                ]);
                $totalGerados++;
            }

            echo json_encode(['success' => true, 'message' => "$totalGerados lançamento(s) de parcelamento cadastrados com sucesso!"]);
            return;
        }

        $data = $base + [
            'valor' => $this->formatDecimal($valor),
            'vencimento' => $vencimento,
            'competencia' => $competencia ?: null,
            'observacoes' => $observacoes,
        ];
        $this->contasPagar_model->add('contas_pagar', $data);
        echo json_encode(['success' => true, 'message' => 'Conta cadastrada com sucesso!']);
    }

    public function cancelarRecorrencia()
    {
        $grupo = $this->input->post('grupoLancamento');
        if (empty($grupo)) {
            echo json_encode(['success' => false, 'message' => 'Grupo não informado.']);
            return;
        }

        $this->contasPagar_model->cancelarGrupoRecorrente($grupo);
        echo json_encode(['success' => true, 'message' => 'Recorrência cancelada. Nenhuma nova conta será gerada a partir de agora.']);
    }

    public function processarRecorrencias()
    {
        $geradas = $this->gerarProximasRecorrencias();
        echo json_encode(['success' => true, 'geradas' => $geradas]);
    }

    private function gerarProximasRecorrencias()
    {
        $ultimas = $this->contasPagar_model->getUltimasRecorrenciasIndeterminadasAtivas();
        $hojeMes = date('Y-m');
        $totalGeradas = 0;

        foreach ($ultimas as $ultima) {
            $limite = 0;
            $vencimentoAtual = $ultima->vencimento;
            $competenciaAtual = $ultima->competencia;
            $indexAtual = (int) $ultima->recorrenteIndex;

            $proximoVencimento = $this->addMonthsClamped($vencimentoAtual, 1);

            while ($proximoVencimento->format('Y-m') <= $hojeMes && $limite < 24) {
                $proximaCompetencia = $competenciaAtual ? $this->addMonthsToCompetencia($competenciaAtual, 1) : null;
                $indexAtual++;

                $this->contasPagar_model->add('contas_pagar', [
                    'idClientes' => $ultima->idClientes,
                    'idUsuarios' => $ultima->idUsuarios,
                    'idEmpresa' => $ultima->idEmpresa,
                    'idUnidade' => $ultima->idUnidade,
                    'idSubUnidade' => $ultima->idSubUnidade,
                    'unidade' => $ultima->unidade,
                    'idCategoria' => $ultima->idCategoria,
                    'idSubCategoria' => $ultima->idSubCategoria,
                    'valor' => $ultima->valor,
                    'vencimento' => $proximoVencimento->format('Y-m-d'),
                    'competencia' => $proximaCompetencia,
                    'observacoes' => $ultima->observacoes,
                    'status' => 'pendente',
                    'recorrente' => 1,
                    'recorrenteIndex' => $indexAtual,
                    'recorrenteTotal' => null,
                    'tipoRepeticao' => 'indeterminado',
                    'grupoLancamento' => $ultima->grupoLancamento,
                ]);

                $totalGeradas++;
                $limite++;

                $vencimentoAtual = $proximoVencimento->format('Y-m-d');
                $competenciaAtual = $proximaCompetencia;
                $proximoVencimento = $this->addMonthsClamped($vencimentoAtual, 1);
            }
        }

        return $totalGeradas;
    }

    private function addMonthsToCompetencia($competencia, $months)
    {
        if (empty($competencia)) {
            return null;
        }

        $date = DateTime::createFromFormat('Y-m-d', $competencia . '-01');
        $date->modify("+$months months");
        return $date->format('Y-m');
    }

    private function addMonthsClamped($baseDate, $months)
    {
        $date = new DateTime($baseDate);
        $dayOfMonth = (int) $date->format('j');
        $date->modify("+$months months");
        $newDayOfMonth = (int) $date->format('j');

        if ($dayOfMonth > 28 && $newDayOfMonth < 4) {
            $date->modify("-$newDayOfMonth days");
        }

        return $date;
    }

    private function gerarParcelas($valorTotal, $entrada, $qtdParcelas, $vencimentoBase)
    {
        $restante = max(0, $valorTotal - $entrada);
        $valorPadrao = round($restante / $qtdParcelas, 2);
        $primeiraParcela = round($restante - $valorPadrao * ($qtdParcelas - 1), 2);

        $parcelas = [];
        $data = new DateTime($vencimentoBase);

        for ($i = 0; $i < $qtdParcelas; $i++) {
            if ($i > 0) {
                $dayOfMonth = (int) $data->format('j');
                $data->modify('+1 month');
                $newDayOfMonth = (int) $data->format('j');
                if ($dayOfMonth > 28 && $newDayOfMonth < 4) {
                    $data->modify("-$newDayOfMonth days");
                }
            }
            $parcelas[] = [
                'valor' => $i === 0 ? $primeiraParcela : $valorPadrao,
                'vencimento' => $data->format('Y-m-d'),
            ];
        }

        return $parcelas;
    }

    public function listar()
    {
        $this->gerarProximasRecorrencias();
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

    public function listarTodasUnidades()
    {
        $unidades = $this->contasPagar_model->getAllUnidades();
        echo json_encode(['data' => $unidades]);
    }

    public function listarTodasSubUnidades()
    {
        $subunidades = $this->contasPagar_model->getAllSubUnidades();
        echo json_encode(['data' => $subunidades]);
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

    public function listarSubCategoriasPorCategoria()
    {
        $idCategoria = $this->input->get('idCategoria');
        $subcategorias = $this->contasPagar_model->getSubCategoriasPorCategoria($idCategoria);
        echo json_encode(['data' => $subcategorias]);
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
