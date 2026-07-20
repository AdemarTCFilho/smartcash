<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-wrench"></i>
                </span>
                <h5>Configurações do Sistema</h5>
            </div>

            <style>
                .tab-content { display: none; }
                .tab-content.active { display: block; }
                .panel { background: #131331; border: 1px solid #131331; border-radius: 4px; padding: 5px; margin-bottom: 20px; }
                .linha-btn { display: flex; gap: 8px; flex-wrap: wrap; }
                .btn {
                    background: #11183D;
                    color: #8E98C6;
                    padding: 8px 14px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 12px;
                    font-weight: 600;
                }
                .btn:hover { background: #121f5e; }
                .btn.active { background: #5358ee; color: #fff; border-color: #5358ee; }
                .form-actions {
                    background: #131331;
                }
            </style>

            <!-- Abas estilo baixasFinanceiras -->
            <div class="panel">
                <div class="linha-btn">
                    <button class="btn active" onclick="openTab('home')">Gerais</button>
                    <button class="btn" onclick="openTab('menu1')">Financeiro</button>
                    <button class="btn" onclick="openTab('menu2')">Produtos</button>
                    <button class="btn" onclick="openTab('menu3')">Notificações</button>
                    <button class="btn" onclick="openTab('menu4')">Atualizações</button>
                    <button class="btn" onclick="openTab('menu5')">OS</button>
                </div>
            </div>

            <?php echo $custom_error; ?>

            <!-- Menu Gerais -->
            <div id="home" class="tab-content active">
                <form action="<?php echo current_url(); ?>" method="post" class="form-horizontal">
                    <div class="control-group">
                        <label for="app_name" class="control-label">Nome do Sistema</label>
                        <div class="controls">
                            <input type="text" required name="app_name" value="<?= $configuration['app_name'] ?>">
                            <span class="help-inline">Nome do sistema</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="app_theme" class="control-label">Tema do Sistema</label>
                        <div class="controls">
                            <select name="app_theme" id="app_theme">
                                <option value="default">Escuro</option>
                                <option value="white" <?= $configuration['app_theme'] == 'white' ? 'selected' : ''; ?>>Claro</option>
                            </select>
                            <span class="help-inline">Selecione o tema que que deseja usar no sistema</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="per_page" class="control-label">Registros por Página</label>
                        <div class="controls">
                            <select name="per_page" id="theme">
                                <option value="10">10</option>
                                <option value="20" <?= $configuration['per_page'] == '20' ? 'selected' : ''; ?>>20</option>
                                <option value="50" <?= $configuration['per_page'] == '50' ? 'selected' : ''; ?>>50</option>
                                <option value="100" <?= $configuration['per_page'] == '100' ? 'selected' : ''; ?>>100</option>
                            </select>
                            <span class="help-inline">Selecione quantos registros deseja exibir nas listas</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="control_datatable" class="control-label">Visualização em DataTables</label>
                        <div class="controls">
                            <select name="control_datatable" id="control_datatable">
                                <option value="1">Sim</option>
                                <option value="0" <?= $configuration['control_datatable'] == '0' ? 'selected' : ''; ?>>Não</option>
                            </select>
                            <span class="help-inline">Ativar ou desativar a visualização em tabelas dinâmicas</span>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="span8">
                            <div class="span9">
                                <button type="submit" class="button btn btn-primary">
                                <span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Salvar Alterações</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Menu Financeiro -->
            <div id="menu1" class="tab-content">
                <form action="<?php echo current_url(); ?>" method="post" class="form-horizontal">
                    <div class="control-group">
                        <label for="control_baixa" class="control-label">Controle de baixa retroativa</label>
                        <div class="controls">
                            <select name="control_baixa" id="control_baixa">
                                <option value="1">Ativar</option>
                                <option value="0" <?= $configuration['control_baixa'] == '0' ? 'selected' : ''; ?>>Desativar</option>
                            </select>
                            <span class="help-inline">Ativar ou desativar o controle de baixa financeira, com data retroativa.</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="control_editos" class="control-label">Controle de edição de OS</label>
                        <div class="controls">
                            <select name="control_editos" id="control_editos">
                                <option value="1" <?= $configuration['control_editos'] == '1' ? 'selected' : ''; ?>>Ativar</option>
                                <option value="0" <?= $configuration['control_editos'] == '0' ? 'selected' : ''; ?>>Desativar</option>
                            </select>
                            <span class="help-inline">Ativar ou desativar a permissão para alterar ou excluir OS faturada e/ou cancelada.</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="control_edit_vendas" class="control-label">Controle de edição de Vendas</label>
                        <div class="controls">
                            <select name="control_edit_vendas" id="control_edit_vendas">
                                <option value="1" <?= $configuration['control_edit_vendas'] == '1' ? 'selected' : ''; ?>>Ativar</option>
                                <option value="0" <?= $configuration['control_edit_vendas'] == '0' ? 'selected' : ''; ?>>Desativar</option>
                            </select>
                            <span class="help-inline">Ativar ou desativar a permissão para alterar ou excluir vendas faturada.</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="pix_key" class="control-label">Chave Pix para Recebimento de Pagamentos</label>
                        <div class="controls">
                            <input type="text" name="pix_key" value="<?= $configuration['pix_key'] ?>">
                            <span class="help-inline">Chave Pix para Recebimento de Pagamentos</span>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="span8">
                            <div class="span9">
                                <button type="submit" class="button btn btn-primary">
                                <span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Salvar Alterações</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Menu Produtos -->
            <div id="menu2" class="tab-content">
                <form action="<?php echo current_url(); ?>" method="post" class="form-horizontal">
                    <div class="control-group">
                        <label for="control_estoque" class="control-label">Controlar Estoque</label>
                        <div class="controls">
                            <select name="control_estoque" id="control_estoque">
                                <option value="1">Ativar</option>
                                <option value="0" <?= $configuration['control_estoque'] == '0' ? 'selected' : ''; ?>>Desativar</option>
                            </select>
                            <span class="help-inline">Ativar ou desativar o controle de estoque.</span>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="span8">
                            <div class="span9">
                                <button type="submit" class="button btn btn-primary">
                                <span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Salvar Alterações</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Menu Notificações -->
            <div id="menu3" class="tab-content">
                <form action="<?php echo current_url(); ?>" method="post" class="form-horizontal">
                    <div class="control-group">
                        <label for="os_notification" class="control-label">Notificação de OS</label>
                        <div class="controls">
                            <select name="os_notification" id="os_notification">
                                <option value="todos">Notificar a Todos</option>
                                <option value="cliente" <?= $configuration['os_notification'] == 'cliente' ? 'selected' : ''; ?>>Somente o Cliente</option>
                                <option value="tecnico" <?= $configuration['os_notification'] == 'tecnico' ? 'selected' : ''; ?>>Somente o Técnico</option>
                                <option value="emitente" <?= $configuration['os_notification'] == 'emitente' ? 'selected' : ''; ?>>Somente o Emitente</option>
                                <option value="nenhum" <?= $configuration['os_notification'] == 'nenhum' ? 'selected' : ''; ?>>Não Notificar</option>
                            </select>
                            <span class="help-inline">Selecione a opção de notificação por e-mail no cadastro de OS.</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="email_automatico" class="control-label">Enviar Email Automático</label>
                        <div class="controls">
                            <select name="email_automatico" id="email_automatico">
                                <option value="1">Ativar</option>
                                <option value="0" <?= $configuration['email_automatico'] == '0' ? 'selected' : ''; ?>>Desativar</option>
                            </select>
                            <span class="help-inline">Ativar ou Desativar a opção de envio de e-mail automático no cadastro de OS.</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="notifica_whats" class="control-label">Notificação do whatsapp</label>
                        <div class="controls">
                            <textarea rows="5" cols="20" name="notifica_whats" id="notifica_whats" placeholder="Use as tags abaixo para criar seu texto!" style="margin: 0px; width: 606px; height: 86px;"><?php echo $configuration['notifica_whats']; ?></textarea>
                        </div>
                        <div class="span3">
                            <label for="notifica_whats_select">Tags de preenchimento<span class="required"></span></label>
                            <select class="span12" name="notifica_whats_select" id="notifica_whats_select" value="">
                                <option value="0">Selecione...</option>
                                <option value="{CLIENTE_NOME}">Nome do Cliente</option>
                                <option value="{NUMERO_OS}">Número da OS</option>
                                <option value="{STATUS_OS}">Status da OS</option>
                                <option value="{VALOR_OS}">Valor da OS</option>
                                <option value="{DESCRI_PRODUTOS}">Descrição produtos</option>
                                <option value="{EMITENTE}">Nome emitente</option>
                                <option value="{TELEFONE_EMITENTE}">Telefone emitente</option>
                                <option value="{OBS_OS}">Observações</option>
                                <option value="{DEFEITO_OS}">Defeitos</option>
                                <option value="{LAUDO_OS}">Laudo</option>
                                <option value="{DATA_FINAL}">Data Final</option>
                                <option value="{DATA_INICIAL}">Data Inicial</option>
                                <option value="{DATA_GARANTIA}">Data da Garantia</option>
                            </select>
                        </div>
                        <span6 class="span10">
                            Para negrito use: *palavra*
                            Para itálico use: _palavra_
                            Para riscado use: ~palavra~
                            </span>
                    </div>
                    <div class="form-actions">
                        <div class="span8">
                            <div class="span9">
                                <button type="submit" class="button btn btn-primary">
                                <span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Salvar Alterações</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Menu Atualização -->
            <div id="menu4" class="tab-content">
                <form action="<?php echo current_url(); ?>" method="post" class="form-horizontal">
                    <div class="form-actions">
                        <div class="span8">
                            <div class="span9" style="display:flex">
                                <button href="#modal-confirmabanco" data-toggle="modal" type="button" class="button btn btn-warning">
                                <span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Banco de Dados</span></button>
                                <button href="#modal-confirmaratualiza" data-toggle="modal" type="button" class="button btn btn-danger">
                                <span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar Mapos</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Menu OS -->
            <div id="menu5" class="tab-content">
                <form action="<?php echo current_url(); ?>" method="post" class="form-horizontal">
                    <div class="control-group">
                        <div class="span8">
                            <span6 class="span10" style="margin-left: 1em;"> Defina a vizualização padrão, onde o que ficar checado será exibida na listagem de OS por padrão. </span6>
                            <div class="span10">
                                <label> <input <?= @in_array("Aberto", json_decode($configuration['os_status_list'])) ? 'checked' : ''; ?> name="os_status_list[]" class="marcar" type="checkbox" value="Aberto"> <span class="lbl"> Aberto</span> </label>
                                <label> <input <?= @in_array("Faturado", json_decode($configuration['os_status_list'])) ? 'checked' : ''; ?> name="os_status_list[]" class="marcar" type="checkbox" value="Faturado"> <span class="lbl"> Faturado</span> </label>
                                <label> <input <?= @in_array("Negociação", json_decode($configuration['os_status_list'])) ? 'checked' : ''; ?> name="os_status_list[]" class="marcar" type="checkbox" value="Negociação"> <span class="lbl"> Negociação</span> </label>
                                <label> <input <?= @in_array("Em Andamento", json_decode($configuration['os_status_list'])) ? 'checked' : ''; ?> name="os_status_list[]" class="marcar" type="checkbox" value="Em Andamento"> <span class="lbl"> Em Andamento</span> </label>
                                <label> <input <?= @in_array("Orçamento", json_decode($configuration['os_status_list'])) ? 'checked' : ''; ?> name="os_status_list[]" class="marcar" type="checkbox" value="Orçamento"> <span class="lbl"> Orçamento</span> </label>
                                <label> <input <?= @in_array("Finalizado", json_decode($configuration['os_status_list'])) ? 'checked' : ''; ?> name="os_status_list[]" class="marcar" type="checkbox" value="Finalizado"> <span class="lbl"> Finalizado</span> </label>
                                <label> <input <?= @in_array("Cancelado", json_decode($configuration['os_status_list'])) ? 'checked' : ''; ?> name="os_status_list[]" class="marcar" type="checkbox" value="Cancelado"> <span class="lbl"> Cancelado</span> </label>
                                <label> <input <?= @in_array("Aguardando Peças", json_decode($configuration['os_status_list'])) ? 'checked' : ''; ?> name="os_status_list[]" class="marcar" type="checkbox" value="Aguardando Peças"> <span class="lbl"> Aguardando Peças </span> </label>
                                <label> <input <?= @in_array("Aprovado", json_decode($configuration['os_status_list'])) ? 'checked' : ''; ?> name="os_status_list[]" class="marcar" type="checkbox" value="Aprovado"> <span class="lbl"> Aprovado </span> </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="span8">
                            <div class="span9">
                                <button type="submit" class="button btn btn-primary">
                                <span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Salvar Alterações</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modal-confirmaratualiza" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/clientes/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Atualização de sistema</h5>
        </div>
        <div class="modal-body">
            <h5 style="text-align: left">Deseja realmente fazer a atualização de sistema?</h5>
            <h7 style="text-align: left">Recomendamos que faça um backup antes de prosseguir!</h7>
            <h7 style="text-align: left"><br>Faça o backup dos seguintes arquivos pois os mesmo serão excluídos:</h7>
            <h7 style="text-align: left"><br>* ./assets/anexos</h7>
            <h7 style="text-align: left"><br>* ./assets/arquivos</h7>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-mini btn-danger" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class='bx bx-x' ></i></span> <span class="button__text2">Cancelar</span></button>
            <button id="update-mapos" type="button" class="button btn btn-warning"><span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar</span></button>
        </div>
    </form>
</div>

<!-- Modal -->
<div id="modal-confirmabanco" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/clientes/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Atualização de sistema</h5>
        </div>
        <div class="modal-body">
            <h5 style="text-align: left">Deseja realmente fazer a atualização do banco de dados?</h5>
            <h7 style="text-align: left">Recomendamos que faça um backup antes de prosseguir!
                <a target="_blank" title="Fazer Bakup" class="btn btn-mini btn-inverse" href="<?php echo site_url() ?>/mapos/backup">Fazer Backup</a>
            </h7>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-mini btn-danger" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class='bx bx-x' ></i></span> <span class="button__text2">Cancelar</span></button>
            <button id="update-database" type="button" class="button btn btn-warning"><span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar</span></button>
        </div>
    </form>
</div>

<script>
    $('#update-database').click(function() {
        window.location = "<?= site_url('mapos/atualizarBanco') ?>"
    });
    $('#update-mapos').click(function() {
        window.location = "<?= site_url('mapos/atualizarMapos') ?>"
    });
    $(document).ready(function() {
        $('#notifica_whats_select').change(function() {
            if ($(this).val() != "0")
                document.getElementById("notifica_whats").value += $(this).val();
            $(this).prop('selectedIndex', 0);
        });
    });

    // Função de abas (estilo baixasFinanceiras)
    function openTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.linha-btn .btn').forEach(el => el.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        event.target.classList.add('active');
    }
</script>