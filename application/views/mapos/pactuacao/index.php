<!-- ===================================================================
     VIEW: Pactuação - Listagem Principal
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
            <?php if($this->session->flashdata('sucesso')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('sucesso') ?>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if($this->session->flashdata('erro')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= $this->session->flashdata('erro') ?>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Cards de Estatísticas -->
            <?php if(!empty($estatisticas)): ?>
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= number_format($estatisticas['total_pactuacoes']) ?></h3>
                            <p>Total de Pactuações</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= number_format($estatisticas['total_cotas']) ?></h3>
                            <p>Total de Cotas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= number_format($estatisticas['total_utilizadas']) ?></h3>
                            <p>Vagas Utilizadas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= number_format($estatisticas['total_disponiveis']) ?></h3>
                            <p>Vagas Disponíveis</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Filtros -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter"></i> Filtros de Pesquisa
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: none;">
                    <?= form_open(current_url(), ['method' => 'GET', 'class' => 'form-horizontal']) ?>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Número Pactuação</label>
                                <?= form_input([
                                    'name' => 'numero_pactuacao',
                                    'value' => isset($filtros_ativos['numero_pactuacao']) ? $filtros_ativos['numero_pactuacao'] : '',
                                    'class' => 'form-control',
                                    'placeholder' => 'Ex: PACT202410001'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Competência</label>
                                <?= form_input([
                                    'name' => 'competencia',
                                    'type' => 'month',
                                    'value' => isset($filtros_ativos['competencia']) ? $filtros_ativos['competencia'] : '',
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Estabelecimento Origem</label>
                                <?= form_dropdown(
                                    'estabelecimento_origem_id',
                                    ['' => 'Todos'] + array_column($opcoes_estabelecimentos, 'nome', 'id'),
                                    isset($filtros_ativos['estabelecimento_origem_id']) ? $filtros_ativos['estabelecimento_origem_id'] : '',
                                    ['class' => 'form-control select2']
                                ) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <?= form_dropdown(
                                    'status_pactuacao',
                                    [
                                        '' => 'Todos',
                                        'ativa' => 'Ativa',
                                        'inativa' => 'Inativa',
                                        'suspensa' => 'Suspensa',
                                        'cancelada' => 'Cancelada'
                                    ],
                                    isset($filtros_ativos['status_pactuacao']) ? $filtros_ativos['status_pactuacao'] : '',
                                    ['class' => 'form-control']
                                ) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Pesquisar
                            </button>
                            <a href="<?= current_url() ?>" class="btn btn-secondary">
                                <i class="fas fa-eraser"></i> Limpar
                            </a>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>

            <!-- Ações -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="<?= base_url('pactuacao/novo') ?>" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nova Pactuação
                    </a>
                    <a href="<?= base_url('pactuacao/dashboard') ?>" class="btn btn-info">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-download"></i> Exportar
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= base_url('pactuacao/relatorio_pdf?' . http_build_query($filtros_ativos)) ?>">
                                <i class="fas fa-file-pdf text-danger"></i> PDF
                            </a>
                            <a class="dropdown-item" href="<?= base_url('pactuacao/exportar_excel?' . http_build_query($filtros_ativos)) ?>">
                                <i class="fas fa-file-excel text-success"></i> Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Pactuações -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> 
                        Pactuações (<?= number_format($total_registros) ?> registros)
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <?php if(!empty($pactuacoes)): ?>
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Competência</th>
                                <th>Estabelecimento Destino</th>
                                <th>Procedimento</th>
                                <th class="text-center">Cota Total</th>
                                <th class="text-center">Utilizadas</th>
                                <th class="text-center">Disponíveis</th>
                                <th class="text-center">% Utilização</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($pactuacoes as $pac): ?>
                            <tr>
                                <td>
                                    <strong><?= $pac['numero_pactuacao'] ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($pac['data_criacao'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <?= date('m/Y', strtotime($pac['competencia'])) ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= ucfirst($pac['tipo_pactuacao']) ?>
                                    </small>
                                </td>
                                <td>
                                    <strong><?= $pac['estabelecimento_destino_nome'] ?></strong>
                                    <?php if($pac['estabelecimento_destino_fantasia']): ?>
                                        <br><small class="text-muted"><?= $pac['estabelecimento_destino_fantasia'] ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= $pac['procedimento_nome'] ?></td>
                                <td class="text-center">
                                    <span class="badge badge-info badge-lg">
                                        <?= number_format($pac['cota_total_mensal']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-warning badge-lg">
                                        <?= number_format($pac['vagas_utilizadas']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success badge-lg">
                                        <?= number_format($pac['vagas_disponivel']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $percentual = $pac['percentual_utilizacao_atual'];
                                    $cor_badge = 'secondary';
                                    if($percentual >= 90) $cor_badge = 'danger';
                                    elseif($percentual >= 70) $cor_badge = 'warning';
                                    elseif($percentual > 0) $cor_badge = 'info';
                                    ?>
                                    <span class="badge badge-<?= $cor_badge ?> badge-lg">
                                        <?= number_format($percentual, 1) ?>%
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $status_cores = [
                                        'ativa' => 'success',
                                        'inativa' => 'secondary',
                                        'suspensa' => 'warning',
                                        'cancelada' => 'danger'
                                    ];
                                                                        isset($status_cores[$pac['status_pactuacao']]) ? $status_cores[$pac['status_pactuacao']] : 'secondary';
                                    ?>
                                    <span class="badge badge-<?= $cor_status ?>">
                                        <?= ucfirst($pac['status_pactuacao']) ?>
                                    </span>
                                    <br>
                                    <?php
                                    $status_util_cores = [
                                        'NORMAL' => 'success',
                                        'ALERTA' => 'warning', 
                                        'CRITICA' => 'danger',
                                        'ESGOTADA' => 'dark',
                                        'SEM_COTA' => 'secondary'
                                    ];
                                    $cor_util = isset($status_util_cores[$pac['status_utilizacao']]) ? $status_util_cores[$pac['status_utilizacao']] : 'secondary';
                                    ?>
                                    <small class="badge badge-<?= $cor_util ?>">
                                        <?= $pac['status_utilizacao'] ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('pactuacao/visualizar/' . $pac['idPactuacao']) ?>" 
                                           class="btn btn-info btn-sm" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('pactuacao/editar/' . $pac['idPactuacao']) ?>" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="confirmarExclusao(<?= $pac['idPactuacao'] ?>)" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Nenhuma pactuação encontrada</h4>
                        <p class="text-muted">Tente ajustar os filtros ou criar uma nova pactuação.</p>
                        <a href="<?= base_url('pactuacao/novo') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nova Pactuação
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if(!empty($paginacao)): ?>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                Mostrando <?= count($pactuacoes) ?> de <?= number_format($total_registros) ?> registros
                            </small>
                        </div>
                        <div class="col-md-6">
                            <?= $paginacao ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modalExclusao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar Exclusão
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir esta pactuação?</p>
                <p><strong>Esta ação não pode ser desfeita!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="btnConfirmarExclusao">
                    <i class="fas fa-trash"></i> Excluir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    // Auto-hide alerts
    $('.alert').delay(5000).fadeOut();
});

let pactuacaoIdExcluir = null;

function confirmarExclusao(id) {
    pactuacaoIdExcluir = id;
    $('#modalExclusao').modal('show');
}

$('#btnConfirmarExclusao').click(function() {
    if (pactuacaoIdExcluir) {
        $.ajax({
            url: '<?= base_url("pactuacao/excluir") ?>',
            type: 'POST',
            data: {
                id: pactuacaoIdExcluir,
                '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                $('#modalExclusao').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                $('#modalExclusao').modal('hide');
                toastr.error('Erro ao processar solicitação');
            }
        });
    }
});
</script>