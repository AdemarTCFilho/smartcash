<!-- ===================================================================
     VIEW: Pactuação - Dashboard
     Compatible com e-SUS Regulação V1.0
     =================================================================== -->

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-chart-pie text-primary"></i> 
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
            
            <!-- Filtros -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter"></i> Filtros do Dashboard
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Competência</label>
                                <?= form_input([
                                    'name' => 'competencia',
                                    'type' => 'month',
                                    'value' => $competencia_selecionada,
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estabelecimento</label>
                                <?= form_dropdown(
                                    'estabelecimento_id',
                                    ['' => 'Todos os estabelecimentos'] + array_column($opcoes_estabelecimentos, 'nome', 'id'),
                                    $estabelecimento_selecionado,
                                    ['class' => 'form-control select2']
                                ) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Atualizar
                                </button>
                                <a href="<?= current_url() ?>" class="btn btn-secondary">
                                    <i class="fas fa-eraser"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>

            <!-- Cards de Estatísticas Gerais -->
            <?php if(!empty($estatisticas_gerais)): ?>
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= number_format($estatisticas_gerais['total_pactuacoes']) ?></h3>
                            <p>Pactuações</p>
                            <div class="small-text">
                                Ativas: <?= number_format($estatisticas_gerais['pactuacoes_ativas']) ?>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= number_format($estatisticas_gerais['total_cotas']) ?></h3>
                            <p>Total de Cotas</p>
                            <div class="small-text">
                                Disponíveis: <?= number_format($estatisticas_gerais['total_disponiveis']) ?>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= number_format($estatisticas_gerais['total_utilizadas']) ?></h3>
                            <p>Vagas Utilizadas</p>
                            <div class="small-text">
                                Bloqueadas: <?= number_format($estatisticas_gerais['total_bloqueadas']) ?>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= number_format($estatisticas_gerais['media_utilizacao'], 1) ?>%</h3>
                            <p>Média de Utilização</p>
                            <div class="small-text">
                                Suspensas: <?= number_format($estatisticas_gerais['pactuacoes_suspensas']) ?>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <!-- Gráfico de Utilização -->
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar"></i> Utilização por Estabelecimento
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="graficoUtilizacao" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Status -->
                <div class="col-md-4">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie"></i> Status das Pactuações
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="graficoStatus" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela Detalhada -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table"></i> Detalhamento das Pactuações
                    </h3>
                    <div class="card-tools">
                        <a href="<?= base_url('pactuacao') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-list"></i> Ver Todas
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <?php if(!empty($dados_dashboard)): ?>
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Pactuação</th>
                                <th>Estabelecimento</th>
                                <th class="text-center">Cota Total</th>
                                <th class="text-center">Utilizadas</th>
                                <th class="text-center">Disponíveis</th>
                                <th class="text-center">% Utilização</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($dados_dashboard as $item): ?>
                            <tr>
                                <td>
                                    <strong><?= $item['numero_pactuacao'] ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= date('m/Y', strtotime($item['competencia'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <?= substr($item['estabelecimento_destino'], 0, 30) ?>
                                    <?php if(strlen($item['estabelecimento_destino']) > 30): ?>...<?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info badge-lg">
                                        <?= number_format($item['cota_total_mensal']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-warning badge-lg">
                                        <?= number_format($item['vagas_utilizadas']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success badge-lg">
                                        <?= number_format($item['vagas_disponivel']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $percentual = $item['percentual_utilizacao'];
                                    $cor_percentual = 'secondary';
                                    if($percentual >= 90) $cor_percentual = 'danger';
                                    elseif($percentual >= 70) $cor_percentual = 'warning';
                                    elseif($percentual > 0) $cor_percentual = 'info';
                                    ?>
                                    <span class="badge badge-<?= $cor_percentual ?> badge-lg">
                                        <?= number_format($percentual, 1) ?>%
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $cores_status = [
                                        'NORMAL' => 'success',
                                        'ALERTA' => 'warning',
                                        'CRITICA' => 'danger', 
                                        'ESGOTADA' => 'dark',
                                        'SEM_COTA' => 'secondary'
                                    ];
                                    $cor_status = isset($cores_status[$item['status_utilizacao']]) ? $cores_status[$item['status_utilizacao']] : 'secondary';
                                    ?>
                                    <span class="badge badge-<?= $cor_status ?>">
                                        <?= $item['status_utilizacao'] ?>
                                    </span>
                                    <br>
                                    <small class="badge badge-<?= $item['status_pactuacao'] == 'ativa' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($item['status_pactuacao']) ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('pactuacao/visualizar/' . $item['idPactuacao']) ?>" 
                                           class="btn btn-info btn-sm" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('pactuacao/editar/' . $item['idPactuacao']) ?>" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Nenhum dado encontrado</h4>
                        <p class="text-muted">Ajuste os filtros ou crie uma nova pactuação.</p>
                        <a href="<?= base_url('pactuacao/novo') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nova Pactuação
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Cards de Indicadores Críticos -->
            <?php if(!empty($dados_dashboard)): ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-exclamation-triangle"></i> Situação Crítica
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php
                            $criticas = array_filter($dados_dashboard, function($item) {
                                return $item['status_utilizacao'] == 'CRITICA' || $item['status_utilizacao'] == 'ESGOTADA';
                            });
                            ?>
                            <?php if(!empty($criticas)): ?>
                                <p><strong><?= count($criticas) ?> pactuações</strong> em situação crítica:</p>
                                <ul class="list-unstyled">
                                    <?php foreach(array_slice($criticas, 0, 5) as $item): ?>
                                    <li>
                                        <small>
                                            <a href="<?= base_url('pactuacao/visualizar/' . $item['idPactuacao']) ?>">
                                                <?= $item['numero_pactuacao'] ?>
                                            </a>
                                            - <?= number_format($item['percentual_utilizacao'], 1) ?>%
                                        </small>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if(count($criticas) > 5): ?>
                                    <small class="text-muted">... e mais <?= count($criticas) - 5 ?> pactuações</small>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-success">
                                    <i class="fas fa-check-circle"></i> 
                                    Nenhuma pactuação em situação crítica
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-exclamation"></i> Alerta
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php
                            $alertas = array_filter($dados_dashboard, function($item) {
                                return $item['status_utilizacao'] == 'ALERTA';
                            });
                            ?>
                            <?php if(!empty($alertas)): ?>
                                <p><strong><?= count($alertas) ?> pactuações</strong> em alerta:</p>
                                <ul class="list-unstyled">
                                    <?php foreach(array_slice($alertas, 0, 5) as $item): ?>
                                    <li>
                                        <small>
                                            <a href="<?= base_url('pactuacao/visualizar/' . $item['idPactuacao']) ?>">
                                                <?= $item['numero_pactuacao'] ?>
                                            </a>
                                            - <?= number_format($item['percentual_utilizacao'], 1) ?>%
                                        </small>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if(count($alertas) > 5): ?>
                                    <small class="text-muted">... e mais <?= count($alertas) - 5 ?> pactuações</small>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-success">
                                    <i class="fas fa-check-circle"></i> 
                                    Nenhuma pactuação em alerta
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-check-circle"></i> Situação Normal
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php
                            $normais = array_filter($dados_dashboard, function($item) {
                                return $item['status_utilizacao'] == 'NORMAL';
                            });
                            ?>
                            <p>
                                <strong><?= count($normais) ?> pactuações</strong> 
                                em situação normal
                            </p>
                            
                            <?php if(!empty($normais)): ?>
                                <p class="text-muted">
                                    <small>
                                        Utilização média: 
                                        <?php
                                        $media_normais = array_sum(array_column($normais, 'percentual_utilizacao')) / count($normais);
                                        echo number_format($media_normais, 1);
                                        ?>%
                                    </small>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- Scripts dos Gráficos -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    // Dados dos gráficos
    const dadosGraficoUtilizacao = <?= $dados_grafico_utilizacao ?>;
    const dadosGraficoStatus = <?= $dados_grafico_status ?>;
    
    // Gráfico de Utilização (Barras)
    const optionsUtilizacao = {
        series: dadosGraficoUtilizacao.series,
        chart: {
            type: 'bar',
            height: 400,
            toolbar: {
                show: true
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: true
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: dadosGraficoUtilizacao.categorias,
            labels: {
                rotate: -45
            }
        },
        yaxis: {
            title: {
                text: 'Quantidade de Vagas'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " vagas"
                }
            }
        },
        colors: ['#ffc107', '#28a745']
    };
    
    const chartUtilizacao = new ApexCharts(document.querySelector("#graficoUtilizacao"), optionsUtilizacao);
    chartUtilizacao.render();
    
    // Gráfico de Status (Pizza)
    const optionsStatus = {
        series: dadosGraficoStatus.map(item => item.y),
        chart: {
            type: 'pie',
            height: 400
        },
        labels: dadosGraficoStatus.map(item => item.name),
        colors: ['#28a745', '#ffc107', '#dc3545', '#6c757d', '#17a2b8'],
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    
    const chartStatus = new ApexCharts(document.querySelector("#graficoStatus"), optionsStatus);
    chartStatus.render();
    
    // Auto-refresh do dashboard a cada 5 minutos
    setInterval(function() {
        location.reload();
    }, 300000); // 5 minutos
});
</script>