<div class="row-fluid" style="margin-top: 0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-diagnoses"></i>
                </span>
                <h5>Relatório de Agendamentos Executados</h5>
            </div>
            <div class="widget-content">
                <div class="span12 well">
                    <form target="_blank" action="<?php echo base_url() ?>index.php/relatorios/AgendamentosExecutadosCustom" method="get">
                        <div class="span12 well">
                            <div class="span2">
                                <label for="dataInicial">Data de:</label>
                                <input type="date" name="dataInicial" class="span12" />
                            </div>
                            <div class="span2">
                                <label for="dataFinal">até:</label>
                                <input type="date" name="dataFinal" class="span12" />
                            </div>
                            <div class="span2">
                                <label for="format">Tipo de impressão:</label>
                                <select name="format" class="span12">
                                    <option value="">PDF</option>
                                    <option value="xls">XLS</option>
                                </select>
                            </div>
                            <div class="span6">
                                <label for="nomeCidadao">Nome do Cidadão:</label>
                                <input id="nomeCidadao" class="span12" type="text" name="nomeCidadao" value="<?php echo isset($result->nome_cidadao) ? $result->nome_cidadao : ''; ?>" />
                                <input id="cidadaos_id" class="span12" type="hidden" name="cidadaos_id" value="<?php echo isset($result->cidadaos_id) ? $result->cidadaos_id : ''; ?>" />
                            </div>
                        </div>
                        <div class="span12" style="display:flex;justify-content: center">
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
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script>
$(function() {
    $("#nomeCidadao").autocomplete({
        source: "<?php echo base_url(); ?>index.php/AgendSolicitante/autoCompleteCidadao",
        minLength: 1,
        select: function(event, ui) {
            $("#cidadaos_id").val(ui.item.id);
            $("#nomeCidadao").val(ui.item.label);
            return false;
        }
    });
});
</script>
