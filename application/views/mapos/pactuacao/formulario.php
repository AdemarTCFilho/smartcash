<!-- ===================================================================
     VIEW: Pactuação - Formulário (Novo/Editar)
     Compatible com e-SUS Regulação V1.0
     =================================================================== -->

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-handshake text-primary"></i> 
                        <?= $titulo_pagina ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <?php foreach($breadcrumb as $item): ?>
                            <?php if(empty($item['url'])): ?>
                                <li class="breadcrumb-item active"><?= $item['titulo'] ?></li>
                            <?php else: ?>
                                <li class="breadcrumb-item">
                                    <a href="<?= $item['url'] ?>"><?= $item['titulo'] ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Alertas -->
            <?php if($this->session->flashdata('erro')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= $this->session->flashdata('erro') ?>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <!-- Erros de Validação -->
            <?php if(validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Erros encontrados:</strong>
                    <?= validation_errors() ?>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?= form_open(current_url(), ['id' => 'formPactuacao', 'class' => 'needs-validation', 'novalidate' => '']) ?>
            
            <!-- Dados Básicos -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Dados Básicos da Pactuação
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numero_pactuacao">Número da Pactuação <span class="text-danger">*</span></label>
                                <?= form_input([
                                    'name' => 'numero_pactuacao',
                                    'id' => 'numero_pactuacao',
                                    'value' => isset($pactuacao) ? $pactuacao['numero_pactuacao'] : (isset($numero_sugerido) ? $numero_sugerido : ''),
                                    'class' => 'form-control',
                                    'placeholder' => 'Ex: PACT202410001',
                                    'readonly' => isset($pactuacao) ? 'readonly' : false
                                ]) ?>
                                <?php if(!isset($pactuacao)): ?>
                                    <small class="form-text text-muted">
                                        Deixe vazio para gerar automaticamente
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="competencia">Competência <span class="text-danger">*</span></label>
                                <?= form_input([
                                    'name' => 'competencia',
                                    'id' => 'competencia',
                                    'type' => 'month',
                                    'value' => isset($pactuacao) ? date('Y-m', strtotime($pactuacao['competencia'])) : date('Y-m'),
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_pactuacao">Tipo de Pactuação</label>
                                <?= form_dropdown(
                                    'tipo_pactuacao',
                                    [
                                        'mensal' => 'Mensal',
                                        'trimestral' => 'Trimestral',
                                        'semestral' => 'Semestral',
                                        'anual' => 'Anual'
                                    ],
                                    isset($pactuacao) ? $pactuacao['tipo_pactuacao'] : 'mensal',
                                    ['id' => 'tipo_pactuacao', 'class' => 'form-control']
                                ) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data_inicio_vigencia">Data Início Vigência <span class="text-danger">*</span></label>
                                <?= form_input([
                                    'name' => 'data_inicio_vigencia',
                                    'id' => 'data_inicio_vigencia',
                                    'type' => 'date',
                                    'value' => isset($pactuacao) ? date('Y-m-d', strtotime($pactuacao['data_inicio_vigencia'])) : '',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data_fim_vigencia">Data Fim Vigência <span class="text-danger">*</span></label>
                                <?= form_input([
                                    'name' => 'data_fim_vigencia',
                                    'id' => 'data_fim_vigencia',
                                    'type' => 'date',
                                    'value' => isset($pactuacao) ? date('Y-m-d', strtotime($pactuacao['data_fim_vigencia'])) : '',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estabelecimentos e Procedimento -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-hospital"></i> Estabelecimentos e Procedimento
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="procedimento_id">Procedimento <span class="text-danger">*</span></label>
                                <?= form_dropdown(
                                    'procedimento_id',
                                    ['' => 'Selecione o procedimento...'] + array_column($opcoes_procedimentos, 'nome', 'id'),
                                    isset($pactuacao) ? $pactuacao['procedimento_id'] : '',
                                    ['id' => 'procedimento_id', 'class' => 'form-control select2', 'required' => true]
                                ) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="estabelecimento_origem_id">Estabelecimento Origem <span class="text-danger">*</span></label>
                                <?= form_dropdown(
                                    'estabelecimento_origem_id',
                                    ['' => 'Selecione a origem...'] + array_column($opcoes_estabelecimentos, 'nome', 'id'),
                                    isset($pactuacao) ? $pactuacao['estabelecimento_origem_id'] : '',
                                    ['id' => 'estabelecimento_origem_id', 'class' => 'form-control select2', 'required' => true]
                                ) ?>
                                <small class="form-text text-muted">Estabelecimento que solicita</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="estabelecimento_destino_id">Estabelecimento Destino <span class="text-danger">*</span></label>
                                <?= form_dropdown(
                                    'estabelecimento_destino_id',
                                    ['' => 'Selecione o destino...'] + array_column($opcoes_estabelecimentos, 'nome', 'id'),
                                    isset($pactuacao) ? $pactuacao['estabelecimento_destino_id'] : '',
                                    ['id' => 'estabelecimento_destino_id', 'class' => 'form-control select2', 'required' => true]
                                ) ?>
                                <small class="form-text text-muted">Estabelecimento que oferece</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="profissional_id">Profissional (Opcional)</label>
                                <?= form_dropdown(
                                    'profissional_id',
                                    ['' => 'Nenhum profissional específico'] + array_column($opcoes_profissionais, 'nome', 'id'),
                                    isset($pactuacao) ? $pactuacao['profissional_id'] : '',
                                    ['id' => 'profissional_id', 'class' => 'form-control select2']
                                ) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="especialidade_id">Especialidade (Opcional)</label>
                                <?= form_dropdown(
                                    'especialidade_id',
                                    ['' => 'Nenhuma especialidade específica'] + array_column($opcoes_especialidades, 'nome', 'id'),
                                    isset($pactuacao) ? $pactuacao['especialidade_id'] : '',
                                    ['id' => 'especialidade_id', 'class' => 'form-control select2']
                                ) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="escala_id">Escala Vinculada (Opcional)</label>
                                <?= form_dropdown(
                                    'escala_id',
                                    ['' => 'Nenhuma escala vinculada'] + array_column($opcoes_escalas, 'nome', 'id'),
                                    isset($pactuacao) ? $pactuacao['escala_id'] : '',
                                    ['id' => 'escala_id', 'class' => 'form-control select2']
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cotas e Limites -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calculator"></i> Cotas e Limites
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cota_total_mensal">Cota Total Mensal <span class="text-danger">*</span></label>
                                <?= form_input([
                                    'name' => 'cota_total_mensal',
                                    'id' => 'cota_total_mensal',
                                    'type' => 'number',
                                    'min' => '1',
                                    'value' => isset($pactuacao) ? $pactuacao['cota_total_mensal'] : '',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cota_primeira_vez">Primeira Vez</label>
                                <?= form_input([
                                    'name' => 'cota_primeira_vez',
                                    'id' => 'cota_primeira_vez',
                                    'type' => 'number',
                                    'min' => '0',
                                    'value' => isset($pactuacao) ? $pactuacao['cota_primeira_vez'] : '0',
                                    'class' => 'form-control cota-parcial'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cota_retorno">Retorno</label>
                                <?= form_input([
                                    'name' => 'cota_retorno',
                                    'id' => 'cota_retorno',
                                    'type' => 'number',
                                    'min' => '0',
                                    'value' => isset($pactuacao) ? $pactuacao['cota_retorno'] : '0',
                                    'class' => 'form-control cota-parcial'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cota_urgencia">Urgência</label>
                                <?= form_input([
                                    'name' => 'cota_urgencia',
                                    'id' => 'cota_urgencia',
                                    'type' => 'number',
                                    'min' => '0',
                                    'value' => isset($pactuacao) ? $pactuacao['cota_urgencia'] : '0',
                                    'class' => 'form-control cota-parcial'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cota_eletiva">Eletiva</label>
                                <?= form_input([
                                    'name' => 'cota_eletiva',
                                    'id' => 'cota_eletiva',
                                    'type' => 'number',
                                    'min' => '0',
                                    'value' => isset($pactuacao) ? $pactuacao['cota_eletiva'] : '0',
                                    'class' => 'form-control cota-parcial'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="percentual_tolerancia">% Tolerância</label>
                                <div class="input-group">
                                    <?= form_input([
                                        'name' => 'percentual_tolerancia',
                                        'id' => 'percentual_tolerancia',
                                        'type' => 'number',
                                        'min' => '0',
                                        'max' => '100',
                                        'step' => '0.01',
                                        'value' => isset($pactuacao) ? $pactuacao['percentual_tolerancia'] : '10.00',
                                        'class' => 'form-control'
                                    ]) ?>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="valor_unitario">Valor Unitário</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <?= form_input([
                                        'name' => 'valor_unitario',
                                        'id' => 'valor_unitario',
                                        'type' => 'number',
                                        'min' => '0',
                                        'step' => '0.01',
                                        'value' => isset($pactuacao) ? $pactuacao['valor_unitario'] : '0.00',
                                        'class' => 'form-control'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="prioridade_regulacao">Prioridade</label>
                                <?= form_dropdown(
                                    'prioridade_regulacao',
                                    [
                                        'baixa' => 'Baixa',
                                        'media' => 'Média',
                                        'alta' => 'Alta',
                                        'critica' => 'Crítica'
                                    ],
                                    isset($pactuacao) ? $pactuacao['prioridade_regulacao'] : 'media',
                                    ['id' => 'prioridade_regulacao', 'class' => 'form-control']
                                ) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div id="alerta-cotas" class="alert alert-warning" style="display: none;">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Atenção:</strong> A soma das cotas específicas não pode ser maior que a cota total.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs"></i> Configurações e Observações
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <?= form_checkbox([
                                        'name' => 'permite_transferencia',
                                        'id' => 'permite_transferencia',
                                        'value' => '1',
                                        'checked' => isset($pactuacao) ? ($pactuacao['permite_transferencia'] == 1) : true,
                                        'class' => 'custom-control-input'
                                    ]) ?>
                                    <label class="custom-control-label" for="permite_transferencia">
                                        Permite Transferência de Vagas
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <?= form_checkbox([
                                        'name' => 'permite_remanejamento',
                                        'id' => 'permite_remanejamento',
                                        'value' => '1',
                                        'checked' => isset($pactuacao) ? ($pactuacao['permite_remanejamento'] == 1) : true,
                                        'class' => 'custom-control-input'
                                    ]) ?>
                                    <label class="custom-control-label" for="permite_remanejamento">
                                        Permite Remanejamento entre Tipos
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="observacoes">Observações</label>
                                <?= form_textarea([
                                    'name' => 'observacoes',
                                    'id' => 'observacoes',
                                    'value' => isset($pactuacao) ? $pactuacao['observacoes'] : '',
                                    'class' => 'form-control',
                                    'rows' => '3',
                                    'placeholder' => 'Observações gerais sobre a pactuação...'
                                ]) ?>
                            </div>
                            
                            <?php if(isset($pactuacao)): ?>
                            <div class="form-group">
                                <label for="justificativa_alteracao">Justificativa da Alteração</label>
                                <?= form_textarea([
                                    'name' => 'justificativa_alteracao',
                                    'id' => 'justificativa_alteracao',
                                    'class' => 'form-control',
                                    'rows' => '2',
                                    'placeholder' => 'Justifique as alterações realizadas...'
                                ]) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="card">
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?= base_url('pactuacao') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-warning" id="btnLimpar">
                                <i class="fas fa-eraser"></i> Limpar
                            </button>
                            <button type="submit" class="btn btn-success" id="btnSalvar">
                                <i class="fas fa-save"></i> 
                                <?= isset($pactuacao) ? 'Atualizar' : 'Salvar' ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <?= form_close() ?>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    // Validação das cotas
    function validarCotas() {
        const cotaTotal = parseInt($('#cota_total_mensal').val()) || 0;
        const cotaPrimeiraVez = parseInt($('#cota_primeira_vez').val()) || 0;
        const cotaRetorno = parseInt($('#cota_retorno').val()) || 0;
        const cotaUrgencia = parseInt($('#cota_urgencia').val()) || 0;
        const cotaEletiva = parseInt($('#cota_eletiva').val()) || 0;
        
        const somaCotas = cotaPrimeiraVez + cotaRetorno + cotaUrgencia + cotaEletiva;
        
        if (somaCotas > cotaTotal) {
            $('#alerta-cotas').show();
            return false;
        } else {
            $('#alerta-cotas').hide();
            return true;
        }
    }
    
    // Validar cotas ao alterar valores
    $('.cota-parcial, #cota_total_mensal').on('input change', validarCotas);
    
    // Gerar número automático se vazio
    $('#competencia').change(function() {
        const competencia = $(this).val();
        const numeroAtual = $('#numero_pactuacao').val();
        
        if (!numeroAtual && competencia) {
            const ano = competencia.substring(0, 4);
            const mes = competencia.substring(5, 7);
            
            $.ajax({
                url: '<?= base_url("pactuacao/ajax_gerar_numero") ?>',
                type: 'GET',
                data: { ano_mes: ano + mes },
                success: function(response) {
                    if (response.numero) {
                        $('#numero_pactuacao').val(response.numero);
                    }
                }
            });
        }
    });
    
    // Limpar formulário
    $('#btnLimpar').click(function() {
        if (confirm('Tem certeza que deseja limpar todos os campos?')) {
            $('#formPactuacao')[0].reset();
            $('.select2').val(null).trigger('change');
            $('#alerta-cotas').hide();
        }
    });
    
    // Validação do formulário
    $('#formPactuacao').submit(function(e) {
        if (!validarCotas()) {
            e.preventDefault();
            toastr.error('Corrija os problemas nas cotas antes de continuar');
            return false;
        }
        
        // Desabilitar botão para evitar duplo clique
        $('#btnSalvar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
    });
    
    // Auto-hide alerts
    $('.alert').delay(5000).fadeOut();
    
    // Calcular valor total
    $('#cota_total_mensal, #valor_unitario').on('input', function() {
        const cota = parseFloat($('#cota_total_mensal').val()) || 0;
        const valor = parseFloat($('#valor_unitario').val()) || 0;
        const total = cota * valor;
        
        if (total > 0) {
            $('#valor_total').text('Valor Total: R$ ' + total.toFixed(2).replace('.', ','));
        }
    });
});
</script>