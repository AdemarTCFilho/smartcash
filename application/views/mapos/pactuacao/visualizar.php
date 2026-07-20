<!-- ===================================================================
     VIEW: Pactuação - Visualizar Detalhes
     Compatible com e-SUS Regulação V1.0
     =================================================================== -->

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-eye text-primary"></i> 
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

            <!-- Ações -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="<?= base_url('pactuacao') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <a href="<?= base_url('pactuacao/editar/' . $pactuacao['idPactuacao']) ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info" onclick="abrirModalTransferencia()">
                            <i class="fas fa-exchange-alt"></i> Transferir Vagas
                        </button>
                        <button type="button" class="btn btn-success" onclick="abrirModalUtilizarVaga()">
                            <i class="fas fa-user-check"></i> Utilizar Vaga
                        </button>
                        <button type="button" class="btn btn-danger" onclick="confirmarExclusao(<?= $pactuacao['idPactuacao'] ?>)">
                            <i class="fas fa-trash"></i> Excluir
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Coluna Principal -->
                <div class="col-md-8">
                    
                    <!-- Informações Básicas -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i> Informações Básicas
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Número da Pactuação:</strong><br>
                                    <span class="text-lg"><?= $pactuacao['numero_pactuacao'] ?></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Competência:</strong><br>
                                    <span class="text-lg"><?= date('m/Y', strtotime($pactuacao['competencia'])) ?></span>
                                    <small class="text-muted">(<?= ucfirst($pactuacao['tipo_pactuacao']) ?>)</small>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Período de Vigência:</strong><br>
                                    <?= date('d/m/Y', strtotime($pactuacao['data_inicio_vigencia'])) ?> até
                                    <?= date('d/m/Y', strtotime($pactuacao['data_fim_vigencia'])) ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Prioridade:</strong><br>
                                    <?php
                                    $cores_prioridade = [
                                        'baixa' => 'success',
                                        'media' => 'info',
                                        'alta' => 'warning',
                                        'critica' => 'danger'
                                    ];
                                    $cor_prioridade = isset($cores_prioridade[$pactuacao['prioridade_regulacao']]) ? $cores_prioridade[$pactuacao['prioridade_regulacao']] : 'secondary';
                                    ?>
                                    <span class="badge badge-<?= $cor_prioridade ?> badge-lg">
                                        <?= ucfirst($pactuacao['prioridade_regulacao']) ?>
                                    </span>
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
                                <div class="col-md-12">
                                    <strong>Procedimento:</strong><br>
                                    <span class="text-lg"><?= $pactuacao['procedimento_nome'] ?></span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Estabelecimento Origem:</strong><br>
                                    <span class="text-primary"><?= $pactuacao['estabelecimento_origem_nome'] ?></span>
                                    <?php if($pactuacao['estabelecimento_origem_fantasia']): ?>
                                        <br><small class="text-muted"><?= $pactuacao['estabelecimento_origem_fantasia'] ?></small>
                                    <?php endif; ?>
                                    <br><small class="text-muted">Estabelecimento que solicita</small>
                                </div>
                                <div class="col-md-6">
                                    <strong>Estabelecimento Destino:</strong><br>
                                    <span class="text-success"><?= $pactuacao['estabelecimento_destino_nome'] ?></span>
                                    <?php if($pactuacao['estabelecimento_destino_fantasia']): ?>
                                        <br><small class="text-muted"><?= $pactuacao['estabelecimento_destino_fantasia'] ?></small>
                                    <?php endif; ?>
                                    <br><small class="text-muted">Estabelecimento que oferece</small>
                                </div>
                            </div>
                            
                            <?php if($pactuacao['profissional_nome'] || $pactuacao['escala_vigencia_inicial']): ?>
                            <hr>
                            <div class="row">
                                <?php if($pactuacao['profissional_nome']): ?>
                                <div class="col-md-6">
                                    <strong>Profissional:</strong><br>
                                    <?= $pactuacao['profissional_nome'] ?>
                                </div>
                                <?php endif; ?>
                                <?php if($pactuacao['escala_vigencia_inicial']): ?>
                                <div class="col-md-6">
                                    <strong>Escala Vinculada:</strong><br>
                                    <?= date('d/m/Y', strtotime($pactuacao['escala_vigencia_inicial'])) ?> - 
                                    <?= date('d/m/Y', strtotime($pactuacao['escala_vigencia_final'])) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Cotas e Utilização -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calculator"></i> Cotas e Utilização
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <div class="info-box bg-info">
                                        <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Cota Total</span>
                                            <span class="info-box-number"><?= number_format($pactuacao['cota_total_mensal']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 text-center">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Utilizadas</span>
                                            <span class="info-box-number"><?= number_format($pactuacao['vagas_utilizadas']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 text-center">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-user-plus"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Disponíveis</span>
                                            <span class="info-box-number"><?= number_format($pactuacao['vagas_disponivel']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 text-center">
                                    <div class="info-box bg-danger">
                                        <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">% Utilização</span>
                                            <span class="info-box-number"><?= number_format($pactuacao['percentual_utilizacao_atual'], 1) ?>%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Barra de Progresso -->
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>Progresso de Utilização:</strong>
                                    <?php
                                    $percentual = $pactuacao['percentual_utilizacao_atual'];
                                    $cor_barra = 'info';
                                    if($percentual >= 90) $cor_barra = 'danger';
                                    elseif($percentual >= 70) $cor_barra = 'warning';
                                    elseif($percentual >= 50) $cor_barra = 'success';
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar bg-<?= $cor_barra ?>" 
                                             style="width: <?= min($percentual, 100) ?>%">
                                            <?= number_format($percentual, 1) ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <!-- Detalhamento das Cotas -->
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Primeira Vez:</strong><br>
                                    <span class="badge badge-primary badge-lg"><?= number_format($pactuacao['cota_primeira_vez']) ?></span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Retorno:</strong><br>
                                    <span class="badge badge-info badge-lg"><?= number_format($pactuacao['cota_retorno']) ?></span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Urgência:</strong><br>
                                    <span class="badge badge-danger badge-lg"><?= number_format($pactuacao['cota_urgencia']) ?></span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Eletiva:</strong><br>
                                    <span class="badge badge-success badge-lg"><?= number_format($pactuacao['cota_eletiva']) ?></span>
                                </div>
                            </div>
                            
                            <?php if($pactuacao['vagas_bloqueadas'] > 0 || $pactuacao['vagas_reservadas'] > 0): ?>
                            <hr>
                            <div class="row">
                                <?php if($pactuacao['vagas_bloqueadas'] > 0): ?>
                                <div class="col-md-6">
                                    <strong>Vagas Bloqueadas:</strong><br>
                                    <span class="badge badge-warning badge-lg"><?= number_format($pactuacao['vagas_bloqueadas']) ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($pactuacao['vagas_reservadas'] > 0): ?>
                                <div class="col-md-6">
                                    <strong>Vagas Reservadas:</strong><br>
                                    <span class="badge badge-secondary badge-lg"><?= number_format($pactuacao['vagas_reservadas']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if(!empty($pactuacao['observacoes'])): ?>
                    <!-- Observações -->
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-sticky-note"></i> Observações
                            </h3>
                        </div>
                        <div class="card-body">
                            <?= nl2br(htmlspecialchars($pactuacao['observacoes'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Coluna Lateral -->
                <div class="col-md-4">
                    
                    <!-- Status da Pactuação -->
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i> Status
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <?php
                                $cores_status = [
                                    'ativa' => 'success',
                                    'inativa' => 'secondary', 
                                    'suspensa' => 'warning',
                                    'cancelada' => 'danger'
                                ];
                                $cor_status = isset($cores_status[$pactuacao['status_pactuacao']]) ? $cores_status[$pactuacao['status_pactuacao']] : 'secondary';
                                ?>
                                <h3 class="text-<?= $cor_status ?>">
                                    <?= ucfirst($pactuacao['status_pactuacao']) ?>
                                </h3>
                                
                                <?php
                                $cores_utilizacao = [
                                    'NORMAL' => 'success',
                                    'ALERTA' => 'warning',
                                    'CRITICA' => 'danger', 
                                    'ESGOTADA' => 'dark',
                                    'SEM_COTA' => 'secondary'
                                ];
                                $cor_utilizacao = isset($cores_utilizacao[$pactuacao['status_utilizacao']]) ? $cores_utilizacao[$pactuacao['status_utilizacao']] : 'secondary';
                                ?>
                                <span class="badge badge-<?= $cor_utilizacao ?> badge-lg">
                                    <?= $pactuacao['status_utilizacao'] ?>
                                </span>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-6">
                                    <strong>Tolerância:</strong><br>
                                    <?= number_format($pactuacao['percentual_tolerancia'], 1) ?>%
                                </div>
                                <div class="col-6">
                                    <strong>Valor Unit.:</strong><br>
                                    R$ <?= number_format($pactuacao['valor_unitario'], 2, ',', '.') ?>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-12">
                                    <strong>Configurações:</strong><br>
                                    <?php if($pactuacao['permite_transferencia']): ?>
                                        <span class="badge badge-success">Permite Transferência</span><br>
                                    <?php endif; ?>
                                    <?php if($pactuacao['permite_remanejamento']): ?>
                                        <span class="badge badge-info">Permite Remanejamento</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações de Auditoria -->
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history"></i> Auditoria
                            </h3>
                        </div>
                        <div class="card-body">
                            <small>
                                <strong>Criado em:</strong><br>
                                <?= date('d/m/Y H:i:s', strtotime($pactuacao['data_criacao'])) ?>
                                <br><br>
                                
                                <?php if($pactuacao['data_alteracao']): ?>
                                <strong>Última alteração:</strong><br>
                                <?= date('d/m/Y H:i:s', strtotime($pactuacao['data_alteracao'])) ?>
                                <br><br>
                                <?php endif; ?>
                                
                                <strong>Versão do Protocolo:</strong><br>
                                <?= $pactuacao['versao_protocolo'] ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Abas de Detalhes -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                                <li class="pt-2 px-3"><h3 class="card-title">Detalhes Adicionais</h3></li>
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-historico" data-toggle="pill" href="#historico" role="tab">
                                        <i class="fas fa-history"></i> Histórico
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-transferencias" data-toggle="pill" href="#transferencias" role="tab">
                                        <i class="fas fa-exchange-alt"></i> Transferências
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-utilizacao" data-toggle="pill" href="#utilizacao" role="tab">
                                        <i class="fas fa-chart-line"></i> Utilização Diária
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabsContent">
                                
                                <!-- Histórico -->
                                <div class="tab-pane fade show active" id="historico" role="tabpanel">
                                    <?php if(!empty($historico)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Data/Hora</th>
                                                    <th>Campo</th>
                                                    <th>Valor Anterior</th>
                                                    <th>Valor Novo</th>
                                                    <th>Usuário</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($historico as $hist): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y H:i', strtotime($hist['data_alteracao'])) ?></td>
                                                    <td><?= $hist['campo_alterado'] ?></td>
                                                    <td><small><?= $hist['valor_anterior'] ?></small></td>
                                                    <td><small><?= $hist['valor_novo'] ?></small></td>
                                                    <td><?= $hist['usuario_nome'] ?: 'Sistema' ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <p class="text-muted text-center">Nenhum histórico de alterações.</p>
                                    <?php endif; ?>
                                </div>

                                <!-- Transferências -->
                                <div class="tab-pane fade" id="transferencias" role="tabpanel">
                                    <?php if(!empty($transferencias)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Tipo</th>
                                                    <th>Origem → Destino</th>
                                                    <th>Qtd Vagas</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($transferencias as $transf): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($transf['data_solicitacao'])) ?></td>
                                                    <td><?= ucfirst($transf['tipo_transferencia']) ?></td>
                                                    <td>
                                                        <small>
                                                            <?= $transf['origem_numero'] ?> → <?= $transf['destino_numero'] ?>
                                                        </small>
                                                    </td>
                                                    <td><?= number_format($transf['quantidade_vagas']) ?></td>
                                                    <td>
                                                        <?php
                                                        $cores_transf = [
                                                            'pendente' => 'warning',
                                                            'aprovada' => 'success',
                                                            'rejeitada' => 'danger',
                                                            'cancelada' => 'secondary'
                                                        ];
                                                        $cor_transf = isset($cores_transf[$transf['status_transferencia']]) ? $cores_transf[$transf['status_transferencia']] : 'secondary';
                                                        ?>
                                                        <span class="badge badge-<?= $cor_transf ?>">
                                                            <?= ucfirst($transf['status_transferencia']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <p class="text-muted text-center">Nenhuma transferência registrada.</p>
                                    <?php endif; ?>
                                </div>

                                <!-- Utilização Diária -->
                                <div class="tab-pane fade" id="utilizacao" role="tabpanel">
                                    <?php if(!empty($utilizacao_diaria)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Tipo Consulta</th>
                                                    <th>Utilizadas</th>
                                                    <th>Agendadas</th>
                                                    <th>Canceladas</th>
                                                    <th>Faltas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($utilizacao_diaria as $util): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($util['data_referencia'])) ?></td>
                                                    <td><?= ucfirst(str_replace('_', ' ', $util['tipo_consulta'])) ?></td>
                                                    <td><?= number_format($util['vagas_utilizadas_dia']) ?></td>
                                                    <td><?= number_format($util['vagas_agendadas_dia']) ?></td>
                                                    <td><?= number_format($util['vagas_canceladas_dia']) ?></td>
                                                    <td><?= number_format($util['vagas_faltaram_dia']) ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <p class="text-muted text-center">Nenhuma utilização registrada no mês atual.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Transferir Vagas -->
<div class="modal fade" id="modalTransferencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">
                    <i class="fas fa-exchange-alt"></i> Transferir Vagas
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formTransferencia">
                <div class="modal-body">
                    <input type="hidden" name="pactuacao_origem_id" value="<?= $pactuacao['idPactuacao'] ?>">
                    
                    <div class="form-group">
                        <label>Pactuação Destino <span class="text-danger">*</span></label>
                        <input type="text" id="pactuacao_destino_busca" class="form-control" 
                               placeholder="Digite para buscar pactuação...">
                        <input type="hidden" name="pactuacao_destino_id" id="pactuacao_destino_id">
                    </div>
                    
                    <div class="form-group">
                        <label>Quantidade de Vagas <span class="text-danger">*</span></label>
                        <input type="number" name="quantidade_vagas" class="form-control" 
                               min="1" max="<?= $pactuacao['vagas_disponivel'] ?>" required>
                        <small class="form-text text-muted">
                            Máximo disponível: <?= number_format($pactuacao['vagas_disponivel']) ?> vagas
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label>Motivo da Transferência <span class="text-danger">*</span></label>
                        <textarea name="motivo" class="form-control" rows="3" 
                                  placeholder="Descreva o motivo da transferência..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-exchange-alt"></i> Transferir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Utilizar Vaga -->
<div class="modal fade" id="modalUtilizarVaga" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">
                    <i class="fas fa-user-check"></i> Utilizar Vaga
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formUtilizarVaga">
                <div class="modal-body">
                    <input type="hidden" name="pactuacao_id" value="<?= $pactuacao['idPactuacao'] ?>">
                    
                    <div class="form-group">
                        <label>Tipo de Consulta <span class="text-danger">*</span></label>
                        <select name="tipo_consulta" class="form-control" required>
                            <option value="">Selecione o tipo...</option>
                            <option value="primeira_vez">Primeira Vez</option>
                            <option value="retorno">Retorno</option>
                            <option value="urgencia">Urgência</option>
                            <option value="eletiva">Eletiva</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Quantidade <span class="text-danger">*</span></label>
                        <input type="number" name="quantidade" class="form-control" 
                               min="1" max="<?= $pactuacao['vagas_disponivel'] ?>" value="1" required>
                        <small class="form-text text-muted">
                            Vagas disponíveis: <?= number_format($pactuacao['vagas_disponivel']) ?>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-check"></i> Utilizar Vaga
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-hide alerts
    $('.alert').delay(5000).fadeOut();
    
    // Autocomplete para busca de pactuações
    $('#pactuacao_destino_busca').autocomplete({
        source: '<?= base_url("pactuacao/ajax_buscar") ?>',
        minLength: 2,
        select: function(event, ui) {
            $('#pactuacao_destino_id').val(ui.item.id);
            $(this).val(ui.item.label);
            return false;
        }
    });
});

function abrirModalTransferencia() {
    <?php if($pactuacao['vagas_disponivel'] <= 0): ?>
        toastr.warning('Não há vagas disponíveis para transferência');
        return;
    <?php endif; ?>
    
    $('#modalTransferencia').modal('show');
}

function abrirModalUtilizarVaga() {
    <?php if($pactuacao['vagas_disponivel'] <= 0): ?>
        toastr.warning('Não há vagas disponíveis para utilização');
        return;
    <?php endif; ?>
    
    $('#modalUtilizarVaga').modal('show');
}

function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir esta pactuação?\n\nEsta ação não pode ser desfeita!')) {
        $.ajax({
            url: '<?= base_url("pactuacao/excluir") ?>',
            type: 'POST',
            data: {
                id: id,
                '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = '<?= base_url("pactuacao") ?>';
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Erro ao processar solicitação');
            }
        });
    }
}

// Submit transferência
$('#formTransferencia').submit(function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '<?= base_url("pactuacao/transferir_vagas") ?>',
        type: 'POST',
        data: $(this).serialize() + '&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>',
        dataType: 'json',
        success: function(response) {
            $('#modalTransferencia').modal('hide');
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
            $('#modalTransferencia').modal('hide');
            toastr.error('Erro ao processar transferência');
        }
    });
});

// Submit utilizar vaga
$('#formUtilizarVaga').submit(function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '<?= base_url("pactuacao/utilizar_vaga") ?>',
        type: 'POST',
        data: $(this).serialize() + '&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>',
        dataType: 'json',
        success: function(response) {
            $('#modalUtilizarVaga').modal('hide');
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
            $('#modalUtilizarVaga').modal('hide');
            toastr.error('Erro ao processar utilização');
        }
    });
});
</script>