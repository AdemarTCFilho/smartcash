<script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/funcoes.js"></script>

<!-- Select2 CSS e JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Estilos para Select2 */
    .select2-container--default .select2-selection--single {
        height: 34px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 32px;
        padding-left: 8px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 32px;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-dropdown {
        z-index: 9999;
    }
</style>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-user"></i>
                </span>
                <h5>Editar Usuário</h5>
            </div>
            <div class="widget-content nopadding tab-content">
                <?php if ($custom_error != '') {
                    echo '<div class="alert alert-danger">' . $custom_error . '</div>';
                } ?>
                <form action="<?php echo current_url(); ?>" id="formUsuario" method="post" class="form-horizontal">
                    <div class="control-group">
                        <?php echo form_hidden('idUsuarios', $result->idUsuarios) ?>
                        <label for="nome" class="control-label">Nome<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome" type="text" name="nome" value="<?php echo $result->nome; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo form_hidden('idUsuarios', $result->idUsuarios) ?>
                        <label for="cns" class="control-label">CNS</label>
                        <div class="controls">
                            <input id="cns" type="text" name="cns" value="<?php echo $result->cns; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <?php echo form_hidden('idUsuarios', $result->idUsuarios) ?>
                        <label for="conselho" class="control-label">Conselho</label>
                        <div class="controls">
                            <input id="conselho" type="text" name="conselho" value="<?php echo $result->conselho; ?>" />
                        </div>
                    </div>

                    <?php

                        $permissao_id_usuario_logado = $this->session->userdata('permissao');
                        if ($permissao_id_usuario_logado == '1') {?>

                        <div class="control-group">
                            <label for="rg" class="control-label">RG</label>
                            <div class="controls">
                                <input id="rg" type="text" name="rg" value="<?php echo $result->rg; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="cpf" class="control-label">CPF</label>
                            <div class="controls">
                                <input class="cpfUser" type="text" name="cpf" value="<?php echo $result->cpf; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="telefone" class="control-label">Telefone</label>
                            <div class="controls">
                                <input id="telefone" type="text" name="telefone" value="<?php echo $result->telefone; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="celular" class="control-label">Celular</label>
                            <div class="controls">
                                <input id="celular" type="text" name="celular" value="<?php echo $result->celular; ?>" />
                            </div>
                        </div>

                        <div class="control-group" class="control-label">
                            <label for="cep" class="control-label">CEP</label>
                            <div class="controls">
                                <input id="cep" type="text" name="cep" value="<?php echo $result->cep; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="rua" class="control-label">Rua</label>
                            <div class="controls">
                                <input id="rua" type="text" name="rua" value="<?php echo $result->rua; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="numero" class="control-label">Numero</label>
                            <div class="controls">
                                <input id="numero" type="text" name="numero" value="<?php echo $result->numero; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="bairro" class="control-label">Bairro</label>
                            <div class="controls">
                                <input id="bairro" type="text" name="bairro" value="<?php echo $result->bairro; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="cidade" class="control-label">Cidade</label>
                            <div class="controls">
                                <input id="cidade" type="text" name="cidade" value="<?php echo $result->cidade; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="estado" class="control-label">Estado</label>
                            <div class="controls">
                                <input id="estado" type="text" name="estado" value="<?php echo $result->estado; ?>" />
                            </div>
                        </div>

                    <?php } ?>

                    <div class="control-group">
                        <label for="email" class="control-label">Email</label>
                        <div class="controls">
                            <input id="email" type="text" name="email" value="<?php echo $result->email; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="senha" class="control-label">Senha</label>
                        <div class="controls">
                            <input id="senha" type="password" name="senha" value="" placeholder="Não preencha se não quiser alterar." />
                            <i class="icon-exclamation-sign tip-top" title="Se não quiser alterar a senha, não preencha esse campo."></i>
                        </div>
                    </div>

                    <!--DATA-->
                    <div class="control-group">
                        <label for="dataExpiracao" class="control-label">Expira em<span class="required">*</span></label>
                        <div class="controls">
                            <input id="dataExpiracao" type="date" name="dataExpiracao" value="<?php echo $result->dataExpiracao; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Situação*</label>
                        <div class="controls">
                            <select name="situacao" id="situacao" class="span6">
                                <?php if ($result->situacao == 1) {
                                    $ativo = 'selected';
                                    $inativo = '';
                                } else {
                                    $ativo = '';
                                    $inativo = 'selected';
                                } ?>
                                <option value="1" <?php echo $ativo; ?>>Ativo</option>
                                <option value="0" <?php echo $inativo; ?>>Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Permissões<span class="required">*</span></label>
                        <div class="controls">
                            <select name="permissoes_id" id="permissoes_id" class="span6">
                                <?php 
                                // Obter permissão do usuário logado
                                $permissao_id_usuario_logado = $this->session->userdata('permissao');
                                
                                foreach ($permissoes as $p) {
                                    // Se o usuário logado não for SuperAdmin (ID 1), não mostrar a opção Administrador
                                    if ($p->nome == 'Administrador' && $permissao_id_usuario_logado != '1') {
                                        continue; // Pula esta opção
                                    }
                                    
                                    if ($p->idPermissao == $result->permissoes_id) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    echo '<option value="' . $p->idPermissao . '"' . $selected . '>' . $p->nome . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="unid_solicitante" class="control-label">Unidade Solicitante</label>
                        <div class="controls">
                            <select class="span6" name="idUnidadeSolicitante" id="idUnidadeSolicitante">
                                <option value="">Selecione uma unidade</option>
                                <?php if (isset($unidades_solicitantes) && !empty($unidades_solicitantes)) : ?>
                                    <?php foreach ($unidades_solicitantes as $unidade) : ?>
                                        <option value="<?php echo $unidade->idUnidadeSolicitante; ?>" 
                                            <?php echo (isset($result->idUnidadeSolicitante) && $result->idUnidadeSolicitante == $unidade->idUnidadeSolicitante) ? 'selected' : ''; ?>>
                                            <?php echo $unidade->nome; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex">
                                <button type="submit" class="button btn btn-primary">
                                  <span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar</span></button>
                                <a href="<?php echo base_url() ?>index.php/usuarios" id="" class="button btn btn-mini btn-warning">
                                  <span class="button__icon"><i class="bx bx-undo"></i></span> <span class="button__text">Voltar</span></a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#formUsuario').validate({
            rules: {
                nome: {
                    required: true
                },
                dataExpiracao: {
                    required: true
                },
                email: {
                    required: true
                }
            },
            messages: {
                nome: {
                    required: 'Campo Requerido.'
                },
                dataExpiracao: {
                    required: 'Campo Requerido.'
                },
                email: {
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

    // Script adicional para Select2 e máscaras
    $(function () {
        // Inicialização das máscaras
        $("#cpf").mask("999.999.999-99");
        $("#telefone").mask("(99) 99999-9999");

        // Inicialização do Select2 para campos dropdown
        if (typeof $.fn.select2 !== 'undefined') {
            $('select').each(function() {
                const $select = $(this);
                
                // Configurações específicas para campo unidade
                if ($select.attr('name') === 'idUnidadeSolicitante') {
                    $select.select2({
                        placeholder: 'Selecione uma unidade...',
                        allowClear: true,
                        language: {
                            noResults: function() {
                                return "Nenhuma unidade encontrada";
                            },
                            searching: function() {
                                return "Pesquisando...";
                            }
                        }
                    });
                } else {
                    // Para outros campos select
                    $select.select2({
                        placeholder: 'Selecione uma opção...',
                        allowClear: false
                    });
                }
            });
        }
    });
</script>
