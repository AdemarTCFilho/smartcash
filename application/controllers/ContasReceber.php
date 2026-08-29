<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ContasReceber extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('contasReceber_model');
        $this->data['menuContasReceber'] = 'Contas a Receber';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vContasReceber')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Contas a Receber.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/contasReceber/gerenciar/';

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'contasReceber/contasReceber';
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

        if (empty($idClientes) || empty($idEmpresa) || empty($idUnidade) || empty($valor) || empty($vencimento)) {
            echo json_encode(['success' => false, 'message' => 'Cliente, Empresa, Unidade, Valor e Vencimento são obrigatórios.']);
            return;
        }

        $base = [
            'idClientes' => $idClientes,
            'idUsuarios' => $this->session->userdata('id'),
            'idEmpresa' => $idEmpresa,
            'idUnidade' => $idUnidade,
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
            $this->contasReceber_model->edit('contas_receber', $data, 'idContaReceber', $id);
            echo json_encode(['success' => true, 'message' => 'Conta atualizada com sucesso!']);
            return;
        }

        $repetir = $this->input->post('repetir');

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
                    $this->contasReceber_model->add('contas_receber', $data);
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
            $this->contasReceber_model->add('contas_receber', $data);

            echo json_encode(['success' => true, 'message' => 'Lançamento recorrente cadastrado com sucesso! Uma nova conta será gerada automaticamente todo mês, até que a recorrência seja cancelada.']);
            return;
        }

        $data = $base + [
            'valor' => $this->formatDecimal($valor),
            'vencimento' => $vencimento,
            'competencia' => $competencia ?: null,
            'observacoes' => $observacoes,
        ];
        $this->contasReceber_model->add('contas_receber', $data);
        echo json_encode(['success' => true, 'message' => 'Conta cadastrada com sucesso!']);
    }

    public function cancelarRecorrencia()
    {
        $grupo = $this->input->post('grupoLancamento');
        if (empty($grupo)) {
            echo json_encode(['success' => false, 'message' => 'Grupo não informado.']);
            return;
        }

        $this->contasReceber_model->cancelarGrupoRecorrente($grupo);
        echo json_encode(['success' => true, 'message' => 'Recorrência cancelada. Nenhuma nova conta será gerada a partir de agora.']);
    }

    public function processarRecorrencias()
    {
        $geradas = $this->gerarProximasRecorrencias();
        echo json_encode(['success' => true, 'geradas' => $geradas]);
    }

    private function gerarProximasRecorrencias()
    {
        $ultimas = $this->contasReceber_model->getUltimasRecorrenciasIndeterminadasAtivas();
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

                $this->contasReceber_model->add('contas_receber', [
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

    public function listar()
    {
        $this->gerarProximasRecorrencias();
        $data = $this->contasReceber_model->getAllContasReceber();
        echo json_encode(['data' => $data]);
    }

    public function listarClientes()
    {
        $clientes = $this->contasReceber_model->getAllClientes();
        echo json_encode(['data' => $clientes]);
    }

    public function listarUsuarios()
    {
        $usuarios = $this->contasReceber_model->getAllUsuarios();
        echo json_encode(['data' => $usuarios]);
    }

    public function listarEmpresas()
    {
        $empresas = $this->contasReceber_model->getAllEmpresas();
        echo json_encode(['data' => $empresas]);
    }

    public function listarUnidadesPorEmpresa()
    {
        $idEmpresa = $this->input->get('idEmpresa');
        $unidades = $this->contasReceber_model->getUnidadesPorEmpresa($idEmpresa);
        echo json_encode(['data' => $unidades]);
    }

    public function listarTodasUnidades()
    {
        $unidades = $this->contasReceber_model->getAllUnidades();
        echo json_encode(['data' => $unidades]);
    }

    public function listarTodasSubUnidades()
    {
        $subunidades = $this->contasReceber_model->getAllSubUnidades();
        echo json_encode(['data' => $subunidades]);
    }

    public function listarSubUnidadesPorUnidade()
    {
        $idUnidade = $this->input->get('idUnidade');
        $subunidades = $this->contasReceber_model->getSubUnidadesPorUnidade($idUnidade);
        echo json_encode(['data' => $subunidades]);
    }

    public function listarCategorias()
    {
        $categorias = $this->contasReceber_model->getAllCategorias();
        echo json_encode(['data' => $categorias]);
    }

    public function listarSubCategoriasPorCategoria()
    {
        $idCategoria = $this->input->get('idCategoria');
        $subcategorias = $this->contasReceber_model->getSubCategoriasPorCategoria($idCategoria);
        echo json_encode(['data' => $subcategorias]);
    }

    public function getDadosDashboard()
    {
        $totalAReceber = $this->contasReceber_model->getTotalAReceber();
        $totalRecebido = $this->contasReceber_model->getTotalRecebido();
        $proximosVencimentos = $this->contasReceber_model->getProximosVencimentos(5);
        $receitasPorCategoria = $this->contasReceber_model->getReceitasPorCategoria();
        $proximoVencimento = null;
        if (!empty($proximosVencimentos)) {
            $proximoVencimento = $proximosVencimentos[0];
        }

        echo json_encode([
            'totalAReceber' => $totalAReceber,
            'totalRecebido' => $totalRecebido,
            'proximoVencimento' => $proximoVencimento,
            'proximosVencimentos' => $proximosVencimentos,
            'receitasPorCategoria' => $receitasPorCategoria,
        ]);
    }

    public function excluir()
    {
        $id = $this->input->post('id');
        $this->contasReceber_model->delete('contas_receber', 'idContaReceber', $id);
        echo json_encode(['success' => true, 'message' => 'Conta excluída com sucesso!']);
    }

    public function getDados()
    {
        $id = $this->input->get('id');
        $data = $this->contasReceber_model->getContaReceberById($id);
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
