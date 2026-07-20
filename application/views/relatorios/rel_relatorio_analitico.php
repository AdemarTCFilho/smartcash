<div class="row-fluid" style="margin-top: 0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-diagnoses"></i>
                </span>
                <h5>Relatório Analítico de Agendamentos</h5>
            </div>
            <div class="widget-content">
                <div class="span12 well">
                    <form target="_blank" action="<?php echo base_url() ?>index.php/relatorios/RelatorioAnalitico" method="get">
                        <div class="span12 well">
                            <div class="span4">
                                <label for="dataInicial">Data de:</label>
                                <input type="date" name="dataInicial" class="span12" />
                            </div>
                            <div class="span4">
                                <label for="dataFinal">até:</label>
                                <input type="date" name="dataFinal" class="span12" />
                            </div>
                        </div>
                        <div class="span12 well" style="margin-left: 0%;">
                            <div class="span4">
                                <label for="nomeCidadao">Nome do Paciente:</label>
                                <input id="nomeCidadao" class="span12" type="text" name="nomeCidadao" />
                            </div>
                            <div class="span2">
                                <label for="cpfCidadao">CPF do Paciente:</label>
                                <input id="cpfCidadao" class="span12" type="text" name="cpfCidadao" placeholder="CPF" />
                            </div>
                            <div class="span3">
                                <label for="profissional">Profissional (Solicitante/Executor):</label>
                                <input id="profissional" class="span12" type="text" name="profissional" />
                            </div>
                            <div class="span3">
                                <label for="unidade">Unidade Solicitante:</label>
                                <select class="span12 select2-unidade" name="unid_solicitantes_id" id="unid_solicitantes_id">
                                    <option value="">Selecione uma unidade</option>
                                    <?php if (isset($unidades_solicitantes) && !empty($unidades_solicitantes)) : ?>
                                        <?php foreach ($unidades_solicitantes as $unidade) : ?>
                                            <option value="<?php echo $unidade->idUnidadeSolicitante; ?>" 
                                                <?php echo (isset($result->unid_solicitantes_id) && $result->unid_solicitantes_id == $unidade->idUnidadeSolicitante) ? 'selected' : ''; ?>>
                                                <?php echo $unidade->nome; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="span12 well" style="margin-left: 0%;">
                            <div class="span4">
                                <label for="especialidade">Especialidade:</label>
                                <input id="especialidade" class="span12" type="text" name="especialidade" />
                            </div>
                            <div class="span4">
                                <label for="status_da_solicitacao">Status da Solicitação:</label>
                                <select name="status_da_solicitacao" class="span12">
                                    <option value="">Todos</option>
                                    <option value="AUTORIZADO">AUTORIZADO</option>
                                    <option value="SOLICITADO">SOLICITADO</option>
                                    <option value="PENDENTE">PENDENTE</option>
                                    <option value="CANCELADO">CANCELADO</option>
                                </select>
                            </div>
                            <div class="span4">
                                <label for="tipo_analise">Tipo de Análise:</label>
                                <select name="tipo_analise" class="span12">
                                    <option value="paciente">Por Paciente</option>
                                    <option value="profissional">Por Profissional</option>
                                    <option value="unidade">Por Unidade</option>
                                    <option value="status">Por Status</option>
                                    <option value="data">Por Data</option>
                                    <option value="especialidade">Por Especialidade</option>
                                </select>
                            </div>
                        </div>
                        <div class="span12 well" style="display:flex;justify-content: center; margin-left: 0%;">
                            <input type="reset" class="button btn btn-warning" value="Limpar" style="justify-content: center">
                            <button class="button btn btn-inverse"><span class="button__icon"><i class="bx bx-printer"></i></span> <span class="button__text2">Imprimir</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function() {
    $("#nomeCidadao").autocomplete({
        source: "<?php echo base_url(); ?>index.php/AgendSolicitante/autoCompleteCidadao",
        minLength: 1,
        select: function(event, ui) {
            $("#nomeCidadao").val(ui.item.label);
            return false;
        }
    });
    $("#profissional").autocomplete({
        source: "<?php echo base_url(); ?>index.php/AgendSolicitante/autoCompleteProfissional",
        minLength: 1
    });
    $("#unidade").autocomplete({
        source: "<?php echo base_url(); ?>index.php/AgendSolicitante/autoCompleteUnidade",
        minLength: 1
    });
    $("#especialidade").autocomplete({
        source: "<?php echo base_url(); ?>index.php/AgendSolicitante/autoCompleteEspecialidade",
        minLength: 1
    });

    // Ativar select2 para unidade solicitante
    $("#unid_solicitantes_id").select2({
        placeholder: "Selecione uma unidade",
        allowClear: true,
        width: '100%'
    });
});
</script>
