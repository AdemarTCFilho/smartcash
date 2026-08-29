<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once(APPPATH . 'third_party/XLSXWriter/XLSXWriter.php');

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

    public function exportarExcel()
    {
        $dados = $this->obterDadosFiltrados();

        $cabecalho = [
            'Cliente' => 'string',
            'Usuário' => 'string',
            'Empresa' => 'string',
            'Unidade' => 'string',
            'Departamento' => 'string',
            'Valor' => 'price',
            'Vencimento' => 'YYYY-MM-DD',
            'Categoria' => 'string',
            'Observações' => 'string',
        ];

        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Contas a Receber', $cabecalho);
        foreach ($dados as $d) {
            $writer->writeSheetRow('Contas a Receber', [
                $d->nomeCliente,
                $d->nomeUsuario,
                $d->nomeEmpresa,
                $d->nomeUnidade,
                $d->nomeSubUnidade,
                $d->valor,
                $d->vencimento,
                $d->nomeCategoria,
                $d->observacoes,
            ]);
        }

        $arquivo = $writer->writeToString();
        $this->load->helper('download');
        force_download('contas_a_receber_' . date('d-m-Y') . '.xlsx', $arquivo);
    }

    public function exportarPdf()
    {
        $dados = $this->obterDadosFiltrados();

        $html = $this->montarHtmlRelatorio(
            $dados,
            'RELATÓRIO CONTAS A RECEBER',
            'Receber de',
            'Total a receber',
            'RECEBIDO'
        );

        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4-L', 'tempDir' => FCPATH . 'assets/uploads/temp/']);
        $mpdf->SetHTMLFooter('<table width="100%" style="font-size:9px; color:#666;"><tr>'
            . '<td style="text-align:left;">Impresso em ' . date('d/m/Y H:i') . '</td>'
            . '<td style="text-align:right;">Página {PAGENO} de {nbpg}</td>'
            . '</tr></table>');
        $mpdf->WriteHTML($html);
        $mpdf->Output(FCPATH . 'assets/uploads/temp/ultimo_export_receber.pdf', 'F');

        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        $mpdf->Output('contas_a_receber_' . date('d-m-Y') . '_' . time() . '.pdf', 'I');
    }

    private function montarHtmlRelatorio($dados, $titulo, $labelPessoa, $labelTotal, $labelStatusLiquidado)
    {
        $emitente = $this->db->get('emitente')->row();

        $logoTag = '';
        $logoPath = FCPATH . 'assets/img/smart-cash-logo-pdf.png';
        if (file_exists($logoPath)) {
            $conteudoLogo = file_get_contents($logoPath);
            if ($conteudoLogo) {
                $logoTag = '<img src="data:image/png;base64,' . base64_encode($conteudoLogo) . '" style="height:40px;">';
            }
        }

        $nomeEmpresa = $emitente ? htmlspecialchars($emitente->nome ?? '') : '';

        $dataDe = $this->input->get('dataDe');
        $dataAte = $this->input->get('dataAte');
        $periodoTexto = '';
        if ($dataDe || $dataAte) {
            $periodoTexto = 'Data entre: ' . ($dataDe ? date('d/m/Y', strtotime($dataDe)) : '...')
                . ' e ' . ($dataAte ? date('d/m/Y', strtotime($dataAte)) : '...');
        }

        $hoje = date('Y-m-d');
        $totalGeral = 0;
        $totalAtrasado = 0;
        foreach ($dados as $d) {
            $totalGeral += (float) $d->valor;
            if ($d->status !== 'liquidado' && $d->vencimento < $hoje) {
                $totalAtrasado += (float) $d->valor;
            }
        }

        $html = '<style>
            body { font-family: Arial, sans-serif; color: #222; }
            table.dados { width:100%; border-collapse: collapse; font-size: 10px; }
            table.dados th { background:#f0f0f0; border-bottom:2px solid #ccc; padding:6px; text-align:left; }
            table.dados td { border-bottom:1px solid #eee; padding:6px; }
            .valor { text-align:right; white-space:nowrap; }
        </style>';

        $html .= '<table width="100%" style="margin-bottom:6px;"><tr>'
            . '<td style="font-size:14px; font-weight:bold;">' . $nomeEmpresa . '</td>'
            . '<td style="text-align:right;">' . $logoTag . '</td>'
            . '</tr><tr>'
            . '<td style="font-size:16px; font-weight:bold;">' . htmlspecialchars($titulo) . '</td>'
            . '<td style="text-align:right; font-size:10px;">' . htmlspecialchars($periodoTexto) . '</td>'
            . '</tr></table>'
            . '<hr style="border:none; border-top:1px solid #999; margin-bottom:8px;">';

        $html .= '<table class="dados"><thead><tr>'
            . '<th width="10%">Data</th>'
            . '<th width="18%">' . htmlspecialchars($labelPessoa) . '</th>'
            . '<th width="40%">Descrição</th>'
            . '<th width="14%">Status</th>'
            . '<th width="18%" class="valor" style="text-align:right;">Valor</th>'
            . '</tr></thead><tbody>';

        foreach ($dados as $d) {
            $statusTexto = $d->status === 'liquidado' ? $labelStatusLiquidado : 'PENDENTE';
            $descricao = $d->observacoes ?: ($d->nomeCategoria ?? '');
            $html .= '<tr>'
                . '<td>' . date('d/m/y', strtotime($d->vencimento)) . '</td>'
                . '<td>' . htmlspecialchars($d->nomeCliente ?? '') . '</td>'
                . '<td>' . htmlspecialchars($descricao) . '</td>'
                . '<td>' . $statusTexto . '</td>'
                . '<td class="valor" style="text-align:right; white-space:nowrap;">' . number_format((float) $d->valor, 2, ',', '.') . '</td>'
                . '</tr>';
        }

        $html .= '</tbody></table>';

        $html .= '<table width="100%" style="margin-top:16px;"><tr><td style="width:60%;"></td><td>'
            . '<table width="100%" style="border:1px solid #ccc; font-size:10px;">'
            . '<tr style="background:#f0f0f0;"><td colspan="2" style="padding:6px; font-weight:bold;">RESUMO</td></tr>'
            . '<tr><td style="padding:4px 6px;">Total de registros:</td><td style="padding:4px 6px; text-align:right;">' . count($dados) . '</td></tr>'
            . '<tr><td style="padding:4px 6px;">Total atrasado:</td><td style="padding:4px 6px; text-align:right;">R$ ' . number_format($totalAtrasado, 2, ',', '.') . '</td></tr>'
            . '<tr><td style="padding:4px 6px; font-weight:bold;">' . htmlspecialchars($labelTotal) . ':</td><td style="padding:4px 6px; text-align:right; font-weight:bold;">R$ ' . number_format($totalGeral, 2, ',', '.') . '</td></tr>'
            . '</table>'
            . '</td></tr></table>';

        return $html;
    }

    private function obterDadosFiltrados()
    {
        $dados = $this->contasReceber_model->getAllContasReceber();

        $busca = strtolower($this->input->get('busca'));
        $dataDe = $this->input->get('dataDe');
        $dataAte = $this->input->get('dataAte');
        $empresa = $this->input->get('empresa');
        $categoria = $this->input->get('categoria');
        $unidade = $this->input->get('unidade');
        $subunidade = $this->input->get('subunidade');
        $usuario = $this->input->get('usuario');

        return array_values(array_filter($dados, function ($d) use ($busca, $dataDe, $dataAte, $empresa, $categoria, $unidade, $subunidade, $usuario) {
            if ($busca && strpos(strtolower($d->nomeCliente ?? ''), $busca) === false) return false;
            if ($dataDe && $d->vencimento < $dataDe) return false;
            if ($dataAte && $d->vencimento > $dataAte) return false;
            if ($empresa && (string) $d->idEmpresa !== (string) $empresa) return false;
            if ($categoria && (string) $d->idCategoria !== (string) $categoria) return false;
            if ($unidade && (string) $d->idUnidade !== (string) $unidade) return false;
            if ($subunidade && (string) $d->idSubUnidade !== (string) $subunidade) return false;
            if ($usuario && (string) $d->idUsuarios !== (string) $usuario) return false;
            return true;
        }));
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
