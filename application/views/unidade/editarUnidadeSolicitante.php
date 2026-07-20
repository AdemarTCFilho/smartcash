<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-wrench"></i>
                </span>
                <h5>Editar Unidade Solicitante</h5>
            </div>
            <div class="widget-content nopadding tab-content">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formUnidadeSolicitante" method="post" class="form-horizontal">
                    <?php echo form_hidden('idUnidadeSolicitante', $result->idUnidadeSolicitante) ?>
                    <div class="control-group">
                        <label for="nome" class="control-label">Nome da unidade<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome" type="text" name="nome" value="<?php echo $result->nome ?>" style="width: 500px; padding: 8px;" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="endereco" class="control-label"><span class="required">Endereço*</span></label>
                        <div class="controls">
                            <input id="endereco" type="text" name="endereco" value="<?php echo $result->endereco ?>" style="width: 500px; padding: 8px;" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="situacao" class="control-label">Situação</label>
                        <div class="controls">
                            <select name="situacao" id="situacao">
                                <option value="1" <?php echo ($result->situacao == 1) ? 'selected' : ''; ?>>Ativo</option>
                                <option value="0" <?php echo ($result->situacao == 0) ? 'selected' : ''; ?>>Inativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content: center">
                                <button type="submit" class="button btn btn-primary" style="max-width: 160px"><span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar</span></button>
                                <a href="<?php echo base_url() ?>index.php/UnidadeSolicitante/gerenciar" id="btnAdicionar" class="button btn btn-mini btn-warning" style="max-width: 160px">
                                  <span class="button__icon"><i class="bx bx-undo"></i></span><span class="button__text2">Voltar</span></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".money").maskMoney();
        
        // Converter campos nome e endereco para MAIÚSCULAS automaticamente
        $('#nome, #endereco').on('input', function() {
            var cursorPosition = this.selectionStart;
            this.value = this.value.toUpperCase();
            this.setSelectionRange(cursorPosition, cursorPosition);
        });
        
        // Também converter quando perder o foco
        $('#nome, #endereco').on('blur', function() {
            this.value = this.value.toUpperCase();
        });
        
        // Converter valores existentes ao carregar a página
        $('#nome').val($('#nome').val().toUpperCase());
        $('#endereco').val($('#endereco').val().toUpperCase());
        
        $('#formUnidadeSolicitante').validate({
            rules: {
                nome: {
                    required: true
                },
                endereco: {
                    required: true
                }
            },
            messages: {
                nome: {
                    required: 'Campo Requerido.'
                },
                endereco: {
                    required: 'Campo Requerido.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });
    });
</script>
