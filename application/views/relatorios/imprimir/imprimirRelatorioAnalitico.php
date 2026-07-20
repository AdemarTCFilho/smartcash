<!DOCTYPE html>
<html>

<head>
    <title>PEP-MED</title>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fullcalendar.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/main.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/blue.css" class="skin-color" />
</head>

<body style="background-color: transparent">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <?= $topo ?>

                    <div class="widget-title">
                        <h4 style="text-align: center; font-size: 1.1em; padding: 5px;">
                            <?= ucfirst($title) ?>
                        </h4>
                        <div style="text-align:center; font-size:0.95em; margin-bottom:8px;">
                            <?php if (!empty($dataInicial) || !empty($dataFinal)): ?>
                                <span>
                                    <?php if (!empty($dataInicial)): ?>
                                        Data Inicial: <strong><?= date('d/m/Y', strtotime($dataInicial)) ?></strong>
                                    <?php endif; ?>
                                    <?php if (!empty($dataFinal)): ?>
                                        &nbsp;até&nbsp; Data Final: <strong><?= date('d/m/Y', strtotime($dataFinal)) ?></strong>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                        <div class="widget-content nopadding tab-content">
                        <?php if (isset($tipo_analise) && $tipo_analise == 'paciente'): ?>
                            <h5 style="margin:10px 0;">Análise Detalhada por Paciente</h5>
                            <table width="100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Paciente</th>
                                        <th>CPF</th>
                                        <th>Data Solicitação</th>
                                        <th>Prof. Solicitante</th>
                                        <th>Unidade</th>
                                        <th>Procedimento</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    <?php foreach ($agendamentos as $a): $total++; ?>
                                    <tr>
                                        <td><?= $a->nome_cidadao ?? '' ?></td>
                                        <td><?= $a->cpf_cidadao ?? '' ?></td>
                                        <td><?= isset($a->data_solicitante) ? date('d/m/Y', strtotime($a->data_solicitante)) : '' ?></td>
                                        <td><?= $a->nome_prof_solicitante ?? '' ?></td>
                                        <td><?= $a->unidade_solicitante ?? '' ?></td>
                                        <td><?= $a->nome_procedimento ?? '' ?></td>
                                        <td><?= $a->status_da_solicitacao ?? '' ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr><td colspan="7" style="text-align:right;font-weight:bold;">Total: <?= $total ?></td></tr>
                                </tfoot>
                            </table>
                        <?php elseif (isset($tipo_analise) && $tipo_analise == 'profissional'): ?>
                            <h5 style="margin:10px 0;">Quantidade de Agendamentos por Profissional</h5>
                            <table width="100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Profissional</th>
                                        <th>Tipo</th>
                                        <th>Quantidade</th>
                                        <th>Tempo Médio (dias)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($analise_profissionais) && is_array($analise_profissionais)): ?>
                                        <?php foreach ($analise_profissionais as $prof): ?>
                                            <tr>
                                                <td><?= $prof['nome'] ?></td>
                                                <td><?= $prof['tipo'] ?></td>
                                                <td><?= $prof['quantidade'] ?></td>
                                                <td><?= $prof['tempo_medio'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php elseif (isset($tipo_analise) && $tipo_analise == 'unidade'): ?>
                            <h5 style="margin:10px 0;">Agendamentos por Unidade de Saúde</h5>
                            <table width="100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Unidade</th>
                                        <th>Especialidade</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($analise_unidades) && is_array($analise_unidades)): ?>
                                        <?php foreach ($analise_unidades as $unid): ?>
                                            <tr>
                                                <td><?= $unid['unidade'] ?></td>
                                                <td><?= $unid['especialidade'] ?></td>
                                                <td><?= $unid['quantidade'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php elseif (isset($tipo_analise) && $tipo_analise == 'status'): ?>
                            <h5 style="margin:10px 0;">Agendamentos por Status</h5>
                            <table width="100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Quantidade</th>
                                        <th>Tempo Médio de Resposta (dias)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($analise_status) && is_array($analise_status)): ?>
                                        <?php foreach ($analise_status as $st): ?>
                                            <tr>
                                                <td><?= $st['status'] ?></td>
                                                <td><?= $st['quantidade'] ?></td>
                                                <td><?= $st['tempo_medio'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php elseif (isset($tipo_analise) && $tipo_analise == 'data'): ?>
                            <h5 style="margin:10px 0;">Agendamentos por Data</h5>
                            <table width="100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Horário</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($analise_datas) && is_array($analise_datas)): ?>
                                        <?php foreach ($analise_datas as $dt): ?>
                                            <tr>
                                                <td><?= $dt['data'] ?></td>
                                                <td><?= $dt['horario'] ?></td>
                                                <td><?= $dt['quantidade'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php elseif (isset($tipo_analise) && $tipo_analise == 'especialidade'): ?>
                            <h5 style="margin:10px 0;">Agendamentos por Especialidade</h5>
                            <table width="100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Especialidade</th>
                                        <th>Profissional Mais Requisitado</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($analise_especialidades) && is_array($analise_especialidades)): ?>
                                        <?php foreach ($analise_especialidades as $esp): ?>
                                            <tr>
                                                <td><?= $esp['especialidade'] ?></td>
                                                <td><?= $esp['profissional'] ?></td>
                                                <td><?= $esp['quantidade'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-info">Nenhum dado analítico para exibir.</div>
                        <?php endif; ?>
                        </div>

                </div>
                <h5 style="text-align: right; font-size: 0.8em; padding: 5px;">Data do Relatório:
                    <?php echo date('d/m/Y'); ?>
                </h5>

            </div>
        </div>
    </div>
</body>

</html>
