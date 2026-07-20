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
                        <table width="100%" class="table table-bordered" style="border:1px solid #ccc; border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px">#</th>
                                    <th style="font-size: 13px">Cidadão</th>
                                    <th style="font-size: 13px">CPF</th>
                                    <th style="font-size: 13px">Prof. Solicitante</th>
                                    <th style="font-size: 13px">Data Solic.</th>
                                    <th style="font-size: 13px">Prof. Saúde Solic.</th>
                                    <th style="font-size: 13px">Unidade Solicitante</th>
                                    <th style="font-size: 13px">It. do Agendamento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                <?php foreach ($agendamentos as $a) : ?>
                                <tr>
                                    <?php if (!empty($a->status_da_solicitacao) && $a->status_da_solicitacao == 'SOLICITADO') { $total++; ?>
                                        <td><?= $a->idAgendamento ?></td>
                                        <td><?= $a->nome_cidadao ?? '' ?></td>
                                        <td><?= $a->cpf_cidadao ?? '' ?></td>
                                        <td><?= $a->nome_prof_solicitante ?? '' ?></td>
                                        <td><?= isset($a->data_solicitante) ? date('d/m/Y', strtotime($a->data_solicitante)) : '' ?></td>
                                        <td><?= $a->nome_prof_solic_saude ?? '' ?></td>
                                        <td><?= $a->unidade_solicitante ?? '' ?></td>
                                        <td><?= $a->nome_procedimento ?? '' ?> - <?= $a->nome_local ?? '' ?></td>
                                    <?php } ?>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="15" style="text-align:right; font-weight:bold; font-size:14px; border-top:1px solid #ccc; border-bottom:1px solid #ccc;">Total de agendamentos: <?= $total ?></td>
                                </tr>
                            </tfoot>
                        </table>
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
