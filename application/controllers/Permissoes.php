<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Permissoes extends MY_Controller
{
    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */

    public function __construct()
    {
        parent::__construct();

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para configurar as permissões no sistema.');
            redirect(base_url());
        }

        $this->load->helper(['form', 'codegen_helper']);
        $this->load->model('permissoes_model');
        $this->data['menuConfiguracoes'] = 'Permissões';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('permissoes/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->permissoes_model->count('permissoes');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->permissoes_model->get('permissoes', 'idPermissao,nome,data,situacao', '', $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'permissoes/permissoes';
        return $this->layout();
    }

    public function adicionar()
    {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $nomePermissao = $this->input->post('nome');
            $cadastro = date('Y-m-d');
            $situacao = 1;

            $permissoes = [

                // UNIDADE GRUPO SAÚDE MASTER

                'aGrupoSaudeMaster' => $this->input->post('aGrupoSaudeMaster'),
                'eGrupoSaudeMaster' => $this->input->post('eGrupoSaudeMaster'),
                'dGrupoSaudeMaster' => $this->input->post('dGrupoSaudeMaster'),
                'vGrupoSaudeMaster' => $this->input->post('vGrupoSaudeMaster'),

                // EMPRESAS / UNIDADES

                'aEmpresasUnidades' => $this->input->post('aEmpresasUnidades'),
                'eEmpresasUnidades' => $this->input->post('eEmpresasUnidades'),
                'dEmpresasUnidades' => $this->input->post('dEmpresasUnidades'),
                'vEmpresasUnidades' => $this->input->post('vEmpresasUnidades'),

                // CLIENTES

                'aClientes' => $this->input->post('aClientes'),
                'eClientes' => $this->input->post('eClientes'),
                'dClientes' => $this->input->post('dClientes'),
                'vClientes' => $this->input->post('vClientes'),

                // CATEGORIA

                'aCategoria' => $this->input->post('aCategoria'),
                'eCategoria' => $this->input->post('eCategoria'),
                'dCategoria' => $this->input->post('dCategoria'),
                'vCategoria' => $this->input->post('vCategoria'),

                // PROJETADO X REALIZADO

                'aProjetadoRealizado' => $this->input->post('aProjetadoRealizado'),
                'eProjetadoRealizado' => $this->input->post('eProjetadoRealizado'),
                'dProjetadoRealizado' => $this->input->post('dProjetadoRealizado'),
                'vProjetadoRealizado' => $this->input->post('vProjetadoRealizado'),

                // METAS FINANCEIRAS

                'aMetasFinanceiras' => $this->input->post('aMetasFinanceiras'),
                'eMetasFinanceiras' => $this->input->post('eMetasFinanceiras'),
                'dMetasFinanceiras' => $this->input->post('dMetasFinanceiras'),
                'vMetasFinanceiras' => $this->input->post('vMetasFinanceiras'),

                // RANKING DE RECEITAS & DESPESAS

                'aRankingReceitasDespesas' => $this->input->post('aRankingReceitasDespesas'),
                'eRankingReceitasDespesas' => $this->input->post('eRankingReceitasDespesas'),
                'dRankingReceitasDespesas' => $this->input->post('dRankingReceitasDespesas'),
                'vRankingReceitasDespesas' => $this->input->post('vRankingReceitasDespesas'),

                // CONTAS A RECEBER

                'aContasReceber' => $this->input->post('aContasReceber'),
                'eContasReceber' => $this->input->post('eContasReceber'),
                'dContasReceber' => $this->input->post('dContasReceber'),
                'vContasReceber' => $this->input->post('vContasReceber'),

                // CONTAS A PAGAR

                'aContasPagar' => $this->input->post('aContasPagar'),
                'eContasPagar' => $this->input->post('eContasPagar'),
                'dContasPagar' => $this->input->post('dContasPagar'),
                'vContasPagar' => $this->input->post('vContasPagar'),

                // BAIXAS FINANCEIRAS

                'aBaixasFinanceiras' => $this->input->post('aBaixasFinanceiras'),
                'eBaixasFinanceiras' => $this->input->post('eBaixasFinanceiras'),
                'dBaixasFinanceiras' => $this->input->post('dBaixasFinanceiras'),
                'vBaixasFinanceiras' => $this->input->post('vBaixasFinanceiras'),

                // CONCILIAÇÃO BANCÁRIA

                'aConciliacaoBancaria' => $this->input->post('aConciliacaoBancaria'),
                'eConciliacaoBancaria' => $this->input->post('eConciliacaoBancaria'),
                'dConciliacaoBancaria' => $this->input->post('dConciliacaoBancaria'),
                'vConciliacaoBancaria' => $this->input->post('vConciliacaoBancaria'),

                // CONTAS BANCÁRIAS

                'aContasBancarias' => $this->input->post('aContasBancarias'),
                'eContasBancarias' => $this->input->post('eContasBancarias'),
                'dContasBancarias' => $this->input->post('dContasBancarias'),
                'vContasBancarias' => $this->input->post('vContasBancarias'),

                // RELATÓRIOS FINANCEIROS

                'aRelatoriosFinanceiros' => $this->input->post('aRelatoriosFinanceiros'),
                'eRelatoriosFinanceiros' => $this->input->post('eRelatoriosFinanceiros'),
                'dRelatoriosFinanceiros' => $this->input->post('dRelatoriosFinanceiros'),
                'vRelatoriosFinanceiros' => $this->input->post('vRelatoriosFinanceiros'),

                // FLUXO DE CAIXA

                'aFluxoCaixa' => $this->input->post('aFluxoCaixa'),
                'eFluxoCaixa' => $this->input->post('eFluxoCaixa'),
                'dFluxoCaixa' => $this->input->post('dFluxoCaixa'),
                'vFluxoCaixa' => $this->input->post('vFluxoCaixa'),

                // DRE GERENCIAL

                'aDreGerencial' => $this->input->post('aDreGerencial'),
                'eDreGerencial' => $this->input->post('eDreGerencial'),
                'dDreGerencial' => $this->input->post('dDreGerencial'),
                'vDreGerencial' => $this->input->post('vDreGerencial'),

                // BOARD EXECUTIVO

                'aBoardExecutivo' => $this->input->post('aBoardExecutivo'),
                'eBoardExecutivo' => $this->input->post('eBoardExecutivo'),
                'dBoardExecutivo' => $this->input->post('dBoardExecutivo'),
                'vBoardExecutivo' => $this->input->post('vBoardExecutivo'),

                // VIGIA IA — PAINEL DE SEGURANÇA

                'aVigiaIa' => $this->input->post('aVigiaIa'),
                'eVigiaIa' => $this->input->post('eVigiaIa'),
                'dVigiaIa' => $this->input->post('dVigiaIa'),
                'vVigiaIa' => $this->input->post('vVigiaIa'),

                // CONFIGURAÇÕES

                'cUsuario' => $this->input->post('cUsuario'),
                'cEmitente' => $this->input->post('cEmitente'),
                'cPermissao' => $this->input->post('cPermissao'),
                'cBackup' => $this->input->post('cBackup'),
                'cAuditoria' => $this->input->post('cAuditoria'),
                'cEmail' => $this->input->post('cEmail'),
                'cSistema' => $this->input->post('cSistema'),
            ];
            foreach ($permissoes as $key => $value) {
                if ($value === null || $value === false) {
                    $permissoes[$key] = '0';
                }
            }
            $permissoes = serialize($permissoes);

            $data = [
                'nome' => $nomePermissao,
                'data' => $cadastro,
                'permissoes' => $permissoes,
                'situacao' => $situacao,
            ];

            if ($this->permissoes_model->add('permissoes', $data) == true) {
                $this->session->set_flashdata('success', 'Permissão adicionada com sucesso!');
                log_info('Adicionou uma permissão');
                redirect(site_url('permissoes/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'permissoes/adicionarPermissao';
        return $this->layout();
    }

    public function editar()
    {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $nomePermissao = $this->input->post('nome');
            $situacao = $this->input->post('situacao');
            $permissoes = [

                // UNIDADE GRUPO SAÚDE MASTER

                'aGrupoSaudeMaster' => $this->input->post('aGrupoSaudeMaster'),
                'eGrupoSaudeMaster' => $this->input->post('eGrupoSaudeMaster'),
                'dGrupoSaudeMaster' => $this->input->post('dGrupoSaudeMaster'),
                'vGrupoSaudeMaster' => $this->input->post('vGrupoSaudeMaster'),

                // EMPRESAS / UNIDADES

                'aEmpresasUnidades' => $this->input->post('aEmpresasUnidades'),
                'eEmpresasUnidades' => $this->input->post('eEmpresasUnidades'),
                'dEmpresasUnidades' => $this->input->post('dEmpresasUnidades'),
                'vEmpresasUnidades' => $this->input->post('vEmpresasUnidades'),

                // CLIENTES

                'aClientes' => $this->input->post('aClientes'),
                'eClientes' => $this->input->post('eClientes'),
                'dClientes' => $this->input->post('dClientes'),
                'vClientes' => $this->input->post('vClientes'),

                // CATEGORIA

                'aCategoria' => $this->input->post('aCategoria'),
                'eCategoria' => $this->input->post('eCategoria'),
                'dCategoria' => $this->input->post('dCategoria'),
                'vCategoria' => $this->input->post('vCategoria'),

                // PROJETADO X REALIZADO

                'aProjetadoRealizado' => $this->input->post('aProjetadoRealizado'),
                'eProjetadoRealizado' => $this->input->post('eProjetadoRealizado'),
                'dProjetadoRealizado' => $this->input->post('dProjetadoRealizado'),
                'vProjetadoRealizado' => $this->input->post('vProjetadoRealizado'),

                // METAS FINANCEIRAS

                'aMetasFinanceiras' => $this->input->post('aMetasFinanceiras'),
                'eMetasFinanceiras' => $this->input->post('eMetasFinanceiras'),
                'dMetasFinanceiras' => $this->input->post('dMetasFinanceiras'),
                'vMetasFinanceiras' => $this->input->post('vMetasFinanceiras'),

                //RANKING DE RECEITAS & DESPESAS

                'aRankingReceitasDespesas' => $this->input->post('aRankingReceitasDespesas'),
                'eRankingReceitasDespesas' => $this->input->post('eRankingReceitasDespesas'),
                'dRankingReceitasDespesas' => $this->input->post('dRankingReceitasDespesas'),
                'vRankingReceitasDespesas' => $this->input->post('vRankingReceitasDespesas'),

                // CONTAS A RECEBER

                'aContasReceber' => $this->input->post('aContasReceber'),
                'eContasReceber' => $this->input->post('eContasReceber'),
                'dContasReceber' => $this->input->post('dContasReceber'),
                'vContasReceber' => $this->input->post('vContasReceber'),

                // CONTAS A PAGAR

                'aContasPagar' => $this->input->post('aContasPagar'),
                'eContasPagar' => $this->input->post('eContasPagar'),
                'dContasPagar' => $this->input->post('dContasPagar'),
                'vContasPagar' => $this->input->post('vContasPagar'),

                // BAIXAS FINANCEIRAS

                'aBaixasFinanceiras' => $this->input->post('aBaixasFinanceiras'),
                'eBaixasFinanceiras' => $this->input->post('eBaixasFinanceiras'),
                'dBaixasFinanceiras' => $this->input->post('dBaixasFinanceiras'),
                'vBaixasFinanceiras' => $this->input->post('vBaixasFinanceiras'),

                // CONCILIAÇÃO BANCÁRIA

                'aConciliacaoBancaria' => $this->input->post('aConciliacaoBancaria'),
                'eConciliacaoBancaria' => $this->input->post('eConciliacaoBancaria'),
                'dConciliacaoBancaria' => $this->input->post('dConciliacaoBancaria'),
                'vConciliacaoBancaria' => $this->input->post('vConciliacaoBancaria'),

                // CONTAS BANCÁRIAS

                'aContasBancarias' => $this->input->post('aContasBancarias'),
                'eContasBancarias' => $this->input->post('eContasBancarias'),
                'dContasBancarias' => $this->input->post('dContasBancarias'),
                'vContasBancarias' => $this->input->post('vContasBancarias'),

                // RELATÓRIOS FINANCEIROS

                'aRelatoriosFinanceiros' => $this->input->post('aRelatoriosFinanceiros'),
                'eRelatoriosFinanceiros' => $this->input->post('eRelatoriosFinanceiros'),
                'dRelatoriosFinanceiros' => $this->input->post('dRelatoriosFinanceiros'),
                'vRelatoriosFinanceiros' => $this->input->post('vRelatoriosFinanceiros'),

                // FLUXO DE CAIXA

                'aFluxoCaixa' => $this->input->post('aFluxoCaixa'),
                'eFluxoCaixa' => $this->input->post('eFluxoCaixa'),
                'dFluxoCaixa' => $this->input->post('dFluxoCaixa'),
                'vFluxoCaixa' => $this->input->post('vFluxoCaixa'),
                
                // DRE GERENCIAL

                'aDreGerencial' => $this->input->post('aDreGerencial'),
                'eDreGerencial' => $this->input->post('eDreGerencial'),
                'dDreGerencial' => $this->input->post('dDreGerencial'),
                'vDreGerencial' => $this->input->post('vDreGerencial'),

                // BOARD EXECUTIVO

                'aBoardExecutivo' => $this->input->post('aBoardExecutivo'),
                'eBoardExecutivo' => $this->input->post('eBoardExecutivo'),
                'dBoardExecutivo' => $this->input->post('dBoardExecutivo'),
                'vBoardExecutivo' => $this->input->post('vBoardExecutivo'),

                // VIGIA IA — PAINEL DE SEGURANÇA

                'aVigiaIa' => $this->input->post('aVigiaIa'),
                'eVigiaIa' => $this->input->post('eVigiaIa'),
                'dVigiaIa' => $this->input->post('dVigiaIa'),
                'vVigiaIa' => $this->input->post('vVigiaIa'),

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                // UNIDADE CIDADAO

                'aCidadao' => $this->input->post('aCidadao'),
                'eCidadao' => $this->input->post('eCidadao'),
                'dCidadao' => $this->input->post('dCidadao'),
                'vCidadao' => $this->input->post('vCidadao'),

                // UNIDADE PROFISSIONAL DE SAÚDE

                'aProfissionalSaude' => $this->input->post('aProfissionalSaude'),
                'eProfissionalSaude' => $this->input->post('eProfissionalSaude'),
                'dProfissionalSaude' => $this->input->post('dProfissionalSaude'),
                'vProfissionalSaude' => $this->input->post('vProfissionalSaude'),

                // UNIDADE SOLICITANTE

                'aUnidadeSolicitante' => $this->input->post('aUnidadeSolicitante'),
                'eUnidadeSolicitante' => $this->input->post('eUnidadeSolicitante'),
                'dUnidadeSolicitante' => $this->input->post('dUnidadeSolicitante'),
                'vUnidadeSolicitante' => $this->input->post('vUnidadeSolicitante'),

                // UNIDADE EXECUTANTE

                'aUnidadeExecutante' => $this->input->post('aUnidadeExecutante'),
                'eUnidadeExecutante' => $this->input->post('eUnidadeExecutante'),
                'dUnidadeExecutante' => $this->input->post('dUnidadeExecutante'),
                'vUnidadeExecutante' => $this->input->post('vUnidadeExecutante'),

                // PROCEDIMENTO

                'aProcedimento' => $this->input->post('aProcedimento'),
                'eProcedimento' => $this->input->post('eProcedimento'),
                'dProcedimento' => $this->input->post('dProcedimento'),
                'vProcedimento' => $this->input->post('vProcedimento'),

                // LOCAL DO PROCEDIMENTO

                'aLocalProcedimento' => $this->input->post('aLocalProcedimento'),
                'eLocalProcedimento' => $this->input->post('eLocalProcedimento'),
                'dLocalProcedimento' => $this->input->post('dLocalProcedimento'),
                'vLocalProcedimento' => $this->input->post('vLocalProcedimento'),

                // ITEM DE AGENDAMENTO

                'aItemAgendamento' => $this->input->post('aItemAgendamento'),
                'eItemAgendamento' => $this->input->post('eItemAgendamento'),
                'dItemAgendamento' => $this->input->post('dItemAgendamento'),
                'vItemAgendamento' => $this->input->post('vItemAgendamento'),

                // AGENDAMENTO SOLICITANTE

                'aAgendamentoSolicitante' => $this->input->post('aAgendamentoSolicitante'),
                'eAgendamentoSolicitante' => $this->input->post('eAgendamentoSolicitante'),
                'dAgendamentoSolicitante' => $this->input->post('dAgendamentoSolicitante'),
                'vAgendamentoSolicitante' => $this->input->post('vAgendamentoSolicitante'),

                // AGENDAMENTO EXECUTANTE

                'aAgendamentoExecutante' => $this->input->post('aAgendamentoExecutante'),
                'eAgendamentoExecutante' => $this->input->post('eAgendamentoExecutante'),
                'dAgendamentoExecutante' => $this->input->post('dAgendamentoExecutante'),
                'vAgendamentoExecutante' => $this->input->post('vAgendamentoExecutante'),

                // TODOS OS AGENDAMENTO

                'aTodosAgendamentos' => $this->input->post('aTodosAgendamentos'),
                'eTodosAgendamentos' => $this->input->post('eTodosAgendamentos'),
                'dTodosAgendamentos' => $this->input->post('dTodosAgendamentos'),
                'vTodosAgendamentos' => $this->input->post('vTodosAgendamentos'),

                // CENTRAL DE MARCAÇÃO

                'aCentralMarcacao' => $this->input->post('aCentralMarcacao'),
                'eCentralMarcacao' => $this->input->post('eCentralMarcacao'),
                'dCentralMarcacao' => $this->input->post('dCentralMarcacao'),
                'vCentralMarcacao' => $this->input->post('vCentralMarcacao'),

                // PARTE ANTIGA

                'aCliente' => $this->input->post('aCliente'),
                'eCliente' => $this->input->post('eCliente'),
                'dCliente' => $this->input->post('dCliente'),
                'vCliente' => $this->input->post('vCliente'),

                'aProduto' => $this->input->post('aProduto'),
                'eProduto' => $this->input->post('eProduto'),
                'dProduto' => $this->input->post('dProduto'),
                'vProduto' => $this->input->post('vProduto'),

                'aServico' => $this->input->post('aServico'),
                'eServico' => $this->input->post('eServico'),
                'dServico' => $this->input->post('dServico'),
                'vServico' => $this->input->post('vServico'),

                'aOs' => $this->input->post('aOs'),
                'eOs' => $this->input->post('eOs'),
                'dOs' => $this->input->post('dOs'),
                'vOs' => $this->input->post('vOs'),

                'aVenda' => $this->input->post('aVenda'),
                'eVenda' => $this->input->post('eVenda'),
                'dVenda' => $this->input->post('dVenda'),
                'vVenda' => $this->input->post('vVenda'),

                'aGarantia' => $this->input->post('aGarantia'),
                'eGarantia' => $this->input->post('eGarantia'),
                'dGarantia' => $this->input->post('dGarantia'),
                'vGarantia' => $this->input->post('vGarantia'),

                'aArquivo' => $this->input->post('aArquivo'),
                'eArquivo' => $this->input->post('eArquivo'),
                'dArquivo' => $this->input->post('dArquivo'),
                'vArquivo' => $this->input->post('vArquivo'),

                'aPagamento' => $this->input->post('aPagamento'),
                'ePagamento' => $this->input->post('ePagamento'),
                'dPagamento' => $this->input->post('dPagamento'),
                'vPagamento' => $this->input->post('vPagamento'),

                'aLancamento' => $this->input->post('aLancamento'),
                'eLancamento' => $this->input->post('eLancamento'),
                'dLancamento' => $this->input->post('dLancamento'),
                'vLancamento' => $this->input->post('vLancamento'),

                'cUsuario' => $this->input->post('cUsuario'),
                'cEmitente' => $this->input->post('cEmitente'),
                'cPermissao' => $this->input->post('cPermissao'),
                'cBackup' => $this->input->post('cBackup'),
                'cAuditoria' => $this->input->post('cAuditoria'),
                'cEmail' => $this->input->post('cEmail'),
                'cSistema' => $this->input->post('cSistema'),

                'rCliente' => $this->input->post('rCliente'),
                'rProduto' => $this->input->post('rProduto'),
                'rServico' => $this->input->post('rServico'),
                'rOs' => $this->input->post('rOs'),
                'rVenda' => $this->input->post('rVenda'),
                'rFinanceiro' => $this->input->post('rFinanceiro'),

                'aCobranca' => $this->input->post('aCobranca'),
                'eCobranca' => $this->input->post('eCobranca'),
                'dCobranca' => $this->input->post('dCobranca'),
                'vCobranca' => $this->input->post('vCobranca'),

            ];
            foreach ($permissoes as $key => $value) {
                if ($value === null || $value === false) {
                    $permissoes[$key] = '0';
                }
            }
            $permissoes = serialize($permissoes);

            $data = [
                'nome' => $nomePermissao,
                'permissoes' => $permissoes,
                'situacao' => $situacao,
            ];

            if ($this->permissoes_model->edit('permissoes', $data, 'idPermissao', $this->input->post('idPermissao')) == true) {
                $this->session->set_flashdata('success', 'Permissão editada com sucesso!');
                log_info('Alterou uma permissão. ID: ' . $this->input->post('idPermissao'));
                redirect(site_url('permissoes/editar/') . $this->input->post('idPermissao'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um errro.</p></div>';
            }
        }

        $this->data['result'] = $this->permissoes_model->getById($this->uri->segment(3));

        $this->data['view'] = 'permissoes/editarPermissao';
        return $this->layout();
    }

    public function desativar()
    {
        $id = $this->input->post('id');
        if (!$id) {
            $this->session->set_flashdata('error', 'Erro ao tentar desativar permissão.');
            redirect(site_url('permissoes/gerenciar/'));
        }
        $data = [
            'situacao' => false,
        ];
        if ($this->permissoes_model->edit('permissoes', $data, 'idPermissao', $id)) {
            log_info('Desativou uma permissão. ID: ' . $id);
            $this->session->set_flashdata('success', 'Permissão desativada com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao desativar permissão!');
        }

        redirect(site_url('permissoes/gerenciar/'));
    }
}

/* End of file permissoes.php */
/* Location: ./application/controllers/permissoes.php */
