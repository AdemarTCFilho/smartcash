
<style>
    .widget-title h5 {
        font-weight : 500;
        padding     : 5px;
        padding-left: 36px !important;
        line-height : 12px;
        margin      : 5px 0 !important;
        font-size   : 1.3em;
        color       : var(--violeta1);
    }

    .icon-cli {
        color: #239683;
        margin-top : 8px;
        margin-left: 8px;
        position   : absolute;
        font-size  : 18px;
    }

    .icon-clic {
        color: #9faab7;
        top: 4px;
        right: 10px;
        position: absolute;
        font-size: 1.9em;
    }

    .icon-clic:hover {
        color: #3fadf6;
    }

    .widget-content {
        padding: 8px 12px 0;
    }

    .table td {
        padding: 5px;
    }

    .table {
        margin-bottom: 0;
    }

    .accordion .widget-box {
        margin-top   : 10px;
        margin-bottom: 0;
        border-radius: 6px;
    }

    .accordion {
        margin-top: -25px;
    }

    .collapse.in {
        top: -15px
    }

    .button {
    min-width: 130px;
    }

    .form-actions {
        padding: 0;
        margin-top: 20px;
        margin-bottom: 20px;
        background-color: transparent;
        border-top: 0px;
    }

    .widget-content table tbody tr:hover {
        background: transparent;
    }

@media (max-width: 480px) {
    .widget-content {
        padding      : 10px 7px !important;
        margin-bottom: -15px;
    }
}


details {
  font-family: sans-serif;
  background: #0d0c2b !important;
  border: 1px solid #201a61;
  border-radius: 8px;
  margin-bottom: 1px;
  padding: 6px;
  transition: all 0.3s ease;
}

/* Estado aberto */
details[open] {
  background: #0d0c2b;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

summary {
  font-weight: 600;
  color: #1a202c;
  cursor: pointer;
  outline: none;
}

summary:hover {
  color: #3182ce;
}

p {
  margin-top: 12px;
  color: #4a5568;
  line-height: 1.6;
  padding: 0 4px;
}

summary {
    display: flex;
    justify-content: space-between; /* Empurra o texto para a esquerda e a seta para a direita */
    align-items: center;
    cursor: pointer;
    font-family: sans-serif;
    font-weight: bold;
    list-style: none; /* Remove a seta nativa (Chrome/Firefox) */
  }

  /* Remove a seta nativa no Safari */
  summary::-webkit-details-marker {
    display: none;
  }

  /* Cria a nova seta no lado direito */
  summary::after {
    content: "▼"; /* Você pode mudar para "❯", "+" ou o ícone que preferir */
    font-size: 12px;
    transition: transform 0.2s ease; /* Efeito suave de rotação */
    color: #fff;
  }

  /* Gira a seta quando o <details> estiver aberto */
  details[open] summary::after {
    transform: rotate(180deg); /* Faz a seta apontar para cima */
  }

  /* Estilo opcional para o texto de dentro */
  details p {
    font-family: sans-serif;
    margin-top: 10px;
    color: #444;
  }

</style>

<?php $permissoes = unserialize($result->permissoes);?>
<div class="span12" style="margin-left: 0">
    <form action="<?php echo base_url();?>index.php/permissoes/editar" id="formPermissao" method="post">
        <div class="span12" style="margin-left: 0">
            <div class="widget-box">
                <div class="widget-title">
               <span class="icon">
               <i class="fas fa-lock"></i>
               </span>
                    <h5 style="padding:12px;padding-left:18px!important;margin:-10px 0 0!important;font-size:1.7em;color: var(--title);">Editar Permissão</h5>
                </div>
                <div class="widget-content">
                    <div class="span4">
                        <label style="color: var(--title);">Nome da Permissão</label>
                        <input name="nome" type="text" id="nome" class="span12" value="<?php echo $result->nome; ?>" />
                        <input type="hidden" name="idPermissao" value="<?php echo $result->idPermissao; ?>">
                    </div>
                    <div class="span3">
                        <label style="color: var(--title);">Situação</label>
                        <select name="situacao" id="situacao" class="span12">
                            <?php if ($result->situacao == 1) {
                                $sim = 'selected';
                                $nao ='';
                            } else {
                                $sim = '';
                                $nao ='selected';
                            }?>
                            <option value="1" <?php echo $sim;?>>Ativo</option>
                            <option value="0" <?php echo $nao;?>>Inativo</option>
                        </select>
                    </div>
                    <div class="span4">
                        <label style="color: var(--title);">
                            <input name="" type="checkbox" value="1" id="marcarTodos" />
                            <span class="lbl"> Marcar Todos</span>
                        </label>
                    </div>

                    <div class="control-group">
                        <label for="documento" class="control-label"></label>
                        <div class="controls">

                    <div class="widget-content" style="padding: 5px 0 !important"><br/><br/>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Grupo Saúde Master</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vGrupoSaudeMaster'])) {
                                                if ($permissoes['vGrupoSaudeMaster'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vGrupoSaudeMaster" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Grupo Saúde Master</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aGrupoSaudeMaster'])) {
                                                    if ($permissoes['aGrupoSaudeMaster'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aGrupoSaudeMaster" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Grupo Saúde Master</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eGrupoSaudeMaster'])) {
                                                    if ($permissoes['eGrupoSaudeMaster'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eGrupoSaudeMaster" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Grupo Saúde Master</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dGrupoSaudeMaster'])) {
                                                    if ($permissoes['dGrupoSaudeMaster'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dGrupoSaudeMaster" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Grupo Saúde Master</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Projetado x Realizado</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vProjetadoRealizado'])) {
                                                if ($permissoes['vProjetadoRealizado'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vProjetadoRealizado" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Projetado x Realizado</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aProjetadoRealizado'])) {
                                                    if ($permissoes['aProjetadoRealizado'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aProjetadoRealizado" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Projetado x Realizado</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eProjetadoRealizado'])) {
                                                    if ($permissoes['eProjetadoRealizado'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eProjetadoRealizado" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Projetado x Realizado</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dProjetadoRealizado'])) {
                                                    if ($permissoes['dProjetadoRealizado'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dProjetadoRealizado" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Projetado x Realizado</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>
                    
                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Metas Financeiras</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vMetasFinanceiras'])) {
                                                if ($permissoes['vMetasFinanceiras'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vMetasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Metas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aMetasFinanceiras'])) {
                                                    if ($permissoes['aMetasFinanceiras'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aMetasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Metas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eMetasFinanceiras'])) {
                                                    if ($permissoes['eMetasFinanceiras'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eMetasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Metas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dMetasFinanceiras'])) {
                                                    if ($permissoes['dMetasFinanceiras'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dMetasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Metas Financeiras</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Ranking por Unidade</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vRankingReceitasDespesas'])) {
                                                if ($permissoes['vRankingReceitasDespesas'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vRankingReceitasDespesas" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);">Visualizar Ranking de Receitas & Despesas</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aRankingReceitasDespesas'])) {
                                                    if ($permissoes['aRankingReceitasDespesas'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aRankingReceitasDespesas" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);">Adicionar Ranking de Receitas & Despesas</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eRankingReceitasDespesas'])) {
                                                    if ($permissoes['eRankingReceitasDespesas'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eRankingReceitasDespesas" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);">Editar Ranking de Receitas & Despesas</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dRankingReceitasDespesas'])) {
                                                    if ($permissoes['dRankingReceitasDespesas'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dRankingReceitasDespesas" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);">Excluir Ranking de Receitas & Despesas</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Contas a Receber</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vContasReceber'])) {
                                                if ($permissoes['vContasReceber'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vContasReceber" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Contas a Receber</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aContasReceber'])) {
                                                    if ($permissoes['aContasReceber'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aContasReceber" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Contas a Receber</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eContasReceber'])) {
                                                    if ($permissoes['eContasReceber'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eContasReceber" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Contas a Receber</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dContasReceber'])) {
                                                    if ($permissoes['dContasReceber'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dContasReceber" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Contas a Receber</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Contas a Pagar</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vContasPagar'])) {
                                                if ($permissoes['vContasPagar'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vContasPagar" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Contas a Pagar</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aContasPagar'])) {
                                                    if ($permissoes['aContasPagar'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aContasPagar" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Contas a Pagar</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eContasPagar'])) {
                                                    if ($permissoes['eContasPagar'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eContasPagar" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Contas a Pagar</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dContasPagar'])) {
                                                    if ($permissoes['dContasPagar'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dContasPagar" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Contas a Pagar</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Baixas Financeiras</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vBaixasFinanceiras'])) {
                                                if ($permissoes['vBaixasFinanceiras'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vBaixasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Baixas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aBaixasFinanceiras'])) {
                                                    if ($permissoes['aBaixasFinanceiras'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aBaixasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Baixas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eBaixasFinanceiras'])) {
                                                    if ($permissoes['eBaixasFinanceiras'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eBaixasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Baixas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dBaixasFinanceiras'])) {
                                                    if ($permissoes['dBaixasFinanceiras'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dBaixasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Baixas Financeiras</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Conciliação Bancária</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vConciliacaoBancaria'])) {
                                                if ($permissoes['vConciliacaoBancaria'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vConciliacaoBancaria" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Conciliação Bancária</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aConciliacaoBancaria'])) {
                                                    if ($permissoes['aConciliacaoBancaria'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aConciliacaoBancaria" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Conciliação Bancária</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eConciliacaoBancaria'])) {
                                                    if ($permissoes['eConciliacaoBancaria'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eConciliacaoBancaria" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Conciliação Bancária</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dConciliacaoBancaria'])) {
                                                    if ($permissoes['dConciliacaoBancaria'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dConciliacaoBancaria" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Conciliação Bancária</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Contas Bancárias</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vContasBancarias'])) {
                                                if ($permissoes['vContasBancarias'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vContasBancarias" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Contas Bancárias</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aContasBancarias'])) {
                                                    if ($permissoes['aContasBancarias'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aContasBancarias" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Contas Bancárias</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eContasBancarias'])) {
                                                    if ($permissoes['eContasBancarias'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eContasBancarias" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Contas Bancárias</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dContasBancarias'])) {
                                                    if ($permissoes['dContasBancarias'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dContasBancarias" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Contas Bancárias</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Relatórios Financeiros</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vRelatoriosFinanceiros'])) {
                                                if ($permissoes['vRelatoriosFinanceiros'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vRelatoriosFinanceiros" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Relatórios Financeiros</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aRelatoriosFinanceiros'])) {
                                                    if ($permissoes['aRelatoriosFinanceiros'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aRelatoriosFinanceiros" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Relatórios Financeiros</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eRelatoriosFinanceiros'])) {
                                                    if ($permissoes['eRelatoriosFinanceiros'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eRelatoriosFinanceiros" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Relatórios Financeiros</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dRelatoriosFinanceiros'])) {
                                                    if ($permissoes['dRelatoriosFinanceiros'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dRelatoriosFinanceiros" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Relatórios Financeiros</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Fluxo de Caixa</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vFluxoCaixa'])) {
                                                if ($permissoes['vFluxoCaixa'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vFluxoCaixa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Fluxo de Caixa</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aFluxoCaixa'])) {
                                                    if ($permissoes['aFluxoCaixa'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aFluxoCaixa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Fluxo de Caixa</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eFluxoCaixa'])) {
                                                    if ($permissoes['eFluxoCaixa'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eFluxoCaixa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Fluxo de Caixa</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dFluxoCaixa'])) {
                                                    if ($permissoes['dFluxoCaixa'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dFluxoCaixa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Fluxo de Caixa</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">DRE Gerencial</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vDreGerencial'])) {
                                                if ($permissoes['vDreGerencial'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar DRE Gerencial</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aDreGerencial'])) {
                                                    if ($permissoes['aDreGerencial'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar DRE Gerencial</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eDreGerencial'])) {
                                                    if ($permissoes['eDreGerencial'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar DRE Gerencial</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dDreGerencial'])) {
                                                    if ($permissoes['dDreGerencial'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir DRE Gerencial</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Board Executivo</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vDreGerencial'])) {
                                                if ($permissoes['vDreGerencial'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Board Executivo</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aDreGerencial'])) {
                                                    if ($permissoes['aDreGerencial'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Board Executivo</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eDreGerencial'])) {
                                                    if ($permissoes['eDreGerencial'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Board Executivo</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dDreGerencial'])) {
                                                    if ($permissoes['dDreGerencial'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Board Executivo</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Vigia IA</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                    <tr>
                                        <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vVigiaIa'])) {
                                                if ($permissoes['vVigiaIa'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vVigiaIa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Vigia IA</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['aVigiaIa'])) {
                                                    if ($permissoes['aVigiaIa'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="aVigiaIa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Vigia IA</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['eVigiaIa'])) {
                                                    if ($permissoes['eVigiaIa'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="eVigiaIa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Vigia IA</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input <?php if (isset($permissoes['dVigiaIa'])) {
                                                    if ($permissoes['dVigiaIa'] == '1') {
                                                        echo 'checked';
                                                    }
                                                }?> name="dVigiaIa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Excluir Vigia IA</span>
                                            </label>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Empresas / Unidades</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vEmpresasUnidades'])) {
                                                if ($permissoes['vEmpresasUnidades'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vEmpresasUnidades" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Visualizar Empresas / Unidades</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aEmpresasUnidades'])) {
                                                if ($permissoes['aEmpresasUnidades'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aEmpresasUnidades" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Adicionar Empresas / Unidades</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eEmpresasUnidades'])) {
                                                if ($permissoes['eEmpresasUnidades'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eEmpresasUnidades" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Editar Empresas / Unidades</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dEmpresasUnidades'])) {
                                                if ($permissoes['dEmpresasUnidades'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dEmpresasUnidades" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Excluir Empresas / Unidades</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Clientes</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vClientes'])) {
                                                if ($permissoes['vClientes'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vClientes" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Visualizar Clientes</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aClientes'])) {
                                                if ($permissoes['aClientes'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aClientes" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Adicionar Clientes</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eClientes'])) {
                                                if ($permissoes['eClientes'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eClientes" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Editar Clientes</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dClientes'])) {
                                                if ($permissoes['dClientes'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dClientes" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Excluir Clientes</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </details>

                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Categoria</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vCategoria'])) {
                                                if ($permissoes['vCategoria'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vCategoria" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Visualizar Categoria</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aCategoria'])) {
                                                if ($permissoes['aCategoria'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aCategoria" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Adicionar Categoria</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eCategoria'])) {
                                                if ($permissoes['eCategoria'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eCategoria" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Editar Categoria</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dCategoria'])) {
                                                if ($permissoes['dCategoria'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dCategoria" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Excluir Categoria</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </details>

                    <!-- CONFIGURAÇÕES E SISTEAS -->
                    <details>
                        <summary>
                            <div class="widget-title">
                                <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                    <span><i class='fa fa-circle icon-cli' style="font-size:16px;color:#201a61;"></i></span>
                                    <h5 style="padding-left: 28px;color: var(--title);">Configurações e Sistema</h5>
                                </a>
                            </div>
                        </summary>
                        <div class="widget-content">
                            <table class="table table-bordered">
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                            <input <?php if (isset($permissoes['cUsuario'])) {
                                                if ($permissoes['cUsuario'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="cUsuario" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Configurar Usuário</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['cEmitente'])) {
                                                if ($permissoes['cEmitente'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="cEmitente" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Configurar Emitente</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['cPermissao'])) {
                                                if ($permissoes['cPermissao'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="cPermissao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Configurar Permissão</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['cBackup'])) {
                                                if ($permissoes['cBackup'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="cBackup" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Backup</span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <input <?php echo (isset($permissoes['cAuditoria']) && $permissoes['cAuditoria'] == 1) ? 'checked' : ''; ?> name="cAuditoria" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Auditoria</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php echo (isset($permissoes['cEmail']) && $permissoes['cEmail'] == 1) ? 'checked' : ''; ?> name="cEmail" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Emails</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php echo (isset($permissoes['cSistema']) && $permissoes['cSistema'] == 1) ? 'checked' : ''; ?> name="cSistema" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Sistema</span>
                                        </label>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </details>
                    
                    <br/>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content: center">
                              <button type="submit" class="button btn btn-primary">
                              <span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Salvar</span></button>
                                <a title="Voltar" class="button btn btn-mini btn-warning" href="<?php echo site_url() ?>/permissoes">
                                  <span class="button__icon"><i class="bx bx-undo"></i></span> <span class="button__text2">Voltar</span></a>
                            </div>
                        </div>
                    </div>

        <!-- <div id="tab1" class="tab-pane active" style="min-height: 300px">
            <div class="accordion" id="collapse-group">

            <div class="accordion-group widget-box">

                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseCidadao" data-toggle="collapse">
                                <span><i class='bx bx-group icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Cidadão</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse in accordion-body" id="collapseCidadao">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vCidadao'])) {
                                            if ($permissoes['vCidadao'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vCidadao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Cidadão</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aCidadao'])) {
                                                if ($permissoes['aCidadao'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aCidadao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Cidadão</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eCidadao'])) {
                                                if ($permissoes['eCidadao'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eCidadao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Cidadão</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dCidadao'])) {
                                                if ($permissoes['dCidadao'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dCidadao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Cidadão</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                                <span><i class='bx bx-group icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Clientes</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGOne">
                        <div class="widget-content">
                        <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vCliente'])) {
                                                if ($permissoes['vCliente'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vCliente" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Cliente</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aCliente'])) {
                                                if ($permissoes['aCliente'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aCliente" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Cliente</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eCliente'])) {
                                                if ($permissoes['eCliente'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eCliente" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Cliente</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dCliente'])) {
                                                if ($permissoes['dCliente'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dCliente" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Cliente</span>
                                        </label>
                                    </td>
                                </tr>
                        </table>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseProfissionais" data-toggle="collapse">
                                <span><i class='bx bx-group icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Profissionais de Saúde</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseProfissionais">
                        <div class="widget-content">
                        <table class="table table-bordered">
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['vProfissionalSaude'])) {
                                                if ($permissoes['vProfissionalSaude'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vProfissionalSaude" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Profissional de Saúde</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aProfissionalSaude'])) {
                                                if ($permissoes['aProfissionalSaude'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aProfissionalSaude" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Profissional de Saúde</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eProfissionalSaude'])) {
                                                if ($permissoes['eProfissionalSaude'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eProfissionalSaude" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Profissional de Saúde</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dProfissionalSaude'])) {
                                                if ($permissoes['dProfissionalSaude'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dProfissionalSaude" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Profissional de Saúde</span>
                                        </label>
                                    </td>
                                </tr>
                        </table>
                    </div>
                </div>
            </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGTwo" data-toggle="collapse">
                                <span><i class='bx bx-package icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Produtos</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGTwo">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vProduto'])) {
                                            if ($permissoes['vProduto'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vProduto" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Produto</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aProduto'])) {
                                                if ($permissoes['aProduto'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aProduto" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Produto</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eProduto'])) {
                                                if ($permissoes['eProduto'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eProduto" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Produto</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dProduto'])) {
                                                if ($permissoes['dProduto'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dProduto" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Produto</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGThree" data-toggle="collapse">
                                <span><i class='bx bx-stopwatch icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Serviços</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGThree">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                            <input <?php if (isset($permissoes['vServico'])) {
                                                if ($permissoes['vServico'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vServico" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Serviço</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aServico'])) {
                                                if ($permissoes['aServico'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aServico" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Serviço</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eServico'])) {
                                                if ($permissoes['eServico'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eServico" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Serviço</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dServico'])) {
                                                if ($permissoes['dServico'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dServico" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Serviço</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGFour" data-toggle="collapse">
                                <span><i class='bx bx-spreadsheet icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Ordens de Serviço</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGFour">
                        <div class="widget-content">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                            <input <?php if (isset($permissoes['vOs'])) {
                                                if ($permissoes['vOs'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vOs" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar OS</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aOs'])) {
                                                if ($permissoes['aOs'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aOs" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar OS</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eOs'])) {
                                                if ($permissoes['eOs'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eOs" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar OS</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dOs'])) {
                                                if ($permissoes['dOs'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dOs" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir OS</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGFive" data-toggle="collapse">
                                <span><i class='bx bx-cart-alt icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Vendas</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGFive">
                        <div class="widget-content">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                            <input <?php if (isset($permissoes['vVenda'])) {
                                                if ($permissoes['vVenda'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vVenda" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Venda</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aVenda'])) {
                                                if ($permissoes['aVenda'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aVenda" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Venda</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eVenda'])) {
                                                if ($permissoes['eVenda'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eVenda" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Venda</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dVenda'])) {
                                                if ($permissoes['dVenda'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dVenda" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Venda</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGSix" data-toggle="collapse">
                                <span><i class='bx bx-credit-card-front icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Cobranças</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGSix">
                        <div class="widget-content">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                            <input <?php if (isset($permissoes['vCobranca'])) {
                                                if ($permissoes['vCobranca'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vCobranca" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Cobranças</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aCobranca'])) {
                                                if ($permissoes['aCobranca'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aCobranca" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Cobranças</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eCobranca'])) {
                                                if ($permissoes['eCobranca'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eCobranca" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Cobranças</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dCobranca'])) {
                                                if ($permissoes['dCobranca'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dCobranca" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Cobranças</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGSeven" data-toggle="collapse">
                                <span><i class='bx bx-receipt icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Garantias</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGSeven">
                        <div class="widget-content">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                            <input <?php if (isset($permissoes['vGarantia'])) {
                                                if ($permissoes['vGarantia'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vGarantia" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Garantia</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aGarantia'])) {
                                                if ($permissoes['aGarantia'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aGarantia" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Garantia</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eGarantia'])) {
                                                if ($permissoes['eGarantia'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eGarantia" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Garantia</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dGarantia'])) {
                                                if ($permissoes['dGarantia'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dGarantia" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Garantia</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGEight" data-toggle="collapse">
                                <span><i class='bx bx-box icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Arquivos</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGEight">
                        <div class="widget-content">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                            <input <?php if (isset($permissoes['vArquivo'])) {
                                                if ($permissoes['vArquivo'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vArquivo" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Arquivo</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aArquivo'])) {
                                                if ($permissoes['aArquivo'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aArquivo" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Arquivo</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eArquivo'])) {
                                                if ($permissoes['eArquivo'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eArquivo" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Arquivo</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dArquivo'])) {
                                                if ($permissoes['dArquivo'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dArquivo" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Arquivo</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGNine" data-toggle="collapse">
                                <span><i class='bx bx-bar-chart-square icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Financeiro</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGNine">
                        <div class="widget-content">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                            <input <?php if (isset($permissoes['vLancamento'])) {
                                                if ($permissoes['vLancamento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="vLancamento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Lançamento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aLancamento'])) {
                                                if ($permissoes['aLancamento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aLancamento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Lançamento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eLancamento'])) {
                                                if ($permissoes['eLancamento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eLancamento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Lançamento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dLancamento'])) {
                                                if ($permissoes['dLancamento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dLancamento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Lançamento</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseUnidadeSolicitante" data-toggle="collapse">
                                <span><i class='bx bx-package icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Unidade Solicitante</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseUnidadeSolicitante">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vUnidadeSolicitante'])) {
                                            if ($permissoes['vUnidadeSolicitante'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vUnidadeSolicitante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Unidade Solicitante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aUnidadeSolicitante'])) {
                                                if ($permissoes['aUnidadeSolicitante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aUnidadeSolicitante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Unidade Solicitante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eUnidadeSolicitante'])) {
                                                if ($permissoes['eUnidadeSolicitante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eUnidadeSolicitante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Unidade Solicitante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dUnidadeSolicitante'])) {
                                                if ($permissoes['dUnidadeSolicitante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dUnidadeSolicitante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Unidade Solicitante</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseUnidadeExecutante" data-toggle="collapse">
                                <span><i class='bx bx-package icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Unidade Executante</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseUnidadeExecutante">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vUnidadeExecutante'])) {
                                            if ($permissoes['vUnidadeExecutante'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vUnidadeExecutante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Unidade Executante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aUnidadeExecutante'])) {
                                                if ($permissoes['aUnidadeExecutante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aUnidadeExecutante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Unidade Executante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eUnidadeExecutante'])) {
                                                if ($permissoes['eUnidadeExecutante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eUnidadeExecutante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Unidade Executante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dUnidadeExecutante'])) {
                                                if ($permissoes['dUnidadeExecutante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dUnidadeExecutante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Unidade Executante</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseProcedimento" data-toggle="collapse">
                                <span><i class='bx bx-package icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Procedimento</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseProcedimento">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vProcedimento'])) {
                                            if ($permissoes['vProcedimento'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vProcedimento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Procedimento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aProcedimento'])) {
                                                if ($permissoes['aProcedimento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aProcedimento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Procedimento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eProcedimento'])) {
                                                if ($permissoes['eProcedimento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eProcedimento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Procedimento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dProcedimento'])) {
                                                if ($permissoes['dUnidadeExecutante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dProcedimento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Procedimento</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseLocalProcedimento" data-toggle="collapse">
                                <span><i class='bx bx-package icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Local do Procedimento</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseLocalProcedimento">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vLocalProcedimento'])) {
                                            if ($permissoes['vLocalProcedimento'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vLocalProcedimento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Local do Procedimento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aLocalProcedimento'])) {
                                                if ($permissoes['aLocalProcedimento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aLocalProcedimento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Local do Procedimento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eLocalProcedimento'])) {
                                                if ($permissoes['eLocalProcedimento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eLocalProcedimento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Local do Procedimento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dLocalProcedimento'])) {
                                                if ($permissoes['dLocalProcedimento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dLocalProcedimento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Local do Procedimento</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseItemAgendamento" data-toggle="collapse">
                                <span><i class='bx bx-package icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Item do Agendamento</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseItemAgendamento">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vItemAgendamento'])) {
                                            if ($permissoes['vItemAgendamento'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vItemAgendamento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Item do Agendamento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aItemAgendamento'])) {
                                                if ($permissoes['aItemAgendamento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aItemAgendamento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Item do Agendamento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eItemAgendamento'])) {
                                                if ($permissoes['eItemAgendamento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eItemAgendamento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Item do Agendamento</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dItemAgendamento'])) {
                                                if ($permissoes['dItemAgendamento'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dItemAgendamento" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Item do Agendamento</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseAgendSolicitante" data-toggle="collapse">
                                <span><i class='bx bx-package icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Agendamento Solicitante</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseAgendSolicitante">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vAgendamentoSolicitante'])) {
                                            if ($permissoes['vAgendamentoSolicitante'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vAgendamentoSolicitante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Agendamento Solicitante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aAgendamentoSolicitante'])) {
                                                if ($permissoes['aAgendamentoSolicitante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aAgendamentoSolicitante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Agendamento Solicitante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eAgendamentoSolicitante'])) {
                                                if ($permissoes['eAgendamentoSolicitante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eAgendamentoSolicitante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Agendamento Solicitante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dAgendamentoSolicitante'])) {
                                                if ($permissoes['dAgendamentoSolicitante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dAgendamentoSolicitante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Agendamento Solicitante</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseAgendExecutante" data-toggle="collapse">
                                <span><i class='bx bx-package icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Agendamento Executante</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseAgendExecutante">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vAgendamentoExecutante'])) {
                                            if ($permissoes['vAgendamentoExecutante'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vAgendamentoExecutante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Agendamento Executante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aAgendamentoExecutante'])) {
                                                if ($permissoes['aAgendamentoExecutante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aAgendamentoExecutante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Agendamento Executante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eAgendamentoExecutante'])) {
                                                if ($permissoes['eAgendamentoExecutante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eAgendamentoExecutante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Agendamento Executante</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dAgendamentoExecutante'])) {
                                                if ($permissoes['dAgendamentoExecutante'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dAgendamentoExecutante" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Agendamento Executante</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseTodosAgendamentos" data-toggle="collapse">
                                <span><i class='bx bx-package icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Todos os Agendamentos</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseTodosAgendamentos">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vTodosAgendamentos'])) {
                                            if ($permissoes['vTodosAgendamentos'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vTodosAgendamentos" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Todos os Agendamentos</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aTodosAgendamentos'])) {
                                                if ($permissoes['aTodosAgendamentos'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aTodosAgendamentos" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Todos os Agendamentos</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eTodosAgendamentos'])) {
                                                if ($permissoes['eTodosAgendamentos'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eTodosAgendamentos" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Todos os Agendamentos</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dTodosAgendamentos'])) {
                                                if ($permissoes['dTodosAgendamentos'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dTodosAgendamentos" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Todos os Agendamentos</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseCentralMarcacao" data-toggle="collapse">
                                <span><i class='bx bx-package icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Central de marcação</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseCentralMarcacao">
                        <div class="widget-content">
                        <table class="table table-bordered">
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input <?php if (isset($permissoes['vCentralMarcacao'])) {
                                            if ($permissoes['vCentralMarcacao'] == '1') {
                                                echo 'checked';
                                            }
                                        }?> name="vCentralMarcacao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Visualizar Central de Marcação</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['aCentralMarcacao'])) {
                                                if ($permissoes['aCentralMarcacao'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="aCentralMarcacao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Adicionar Central de Marcação</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['eCentralMarcacao'])) {
                                                if ($permissoes['eCentralMarcacao'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="eCentralMarcacao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Editar Central de Marcação</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['dCentralMarcacao'])) {
                                                if ($permissoes['dCentralMarcacao'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="dCentralMarcacao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Excluir Central de Marcação</span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGTen" data-toggle="collapse">
                                <span><i class='bx bx-chart icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Relatórios</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGTen">
                        <div class="widget-content">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                            <input <?php if (isset($permissoes['rCliente'])) {
                                                if ($permissoes['rCliente'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="rCliente" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Relatório Cliente</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['rServico'])) {
                                                if ($permissoes['rServico'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="rServico" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Relatório Serviço</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['rOs'])) {
                                                if ($permissoes['rOs'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="rOs" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Relatório OS</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['rProduto'])) {
                                                if ($permissoes['rProduto'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="rProduto" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Relatório Produto</span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['rVenda'])) {
                                                if ($permissoes['rVenda'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="rVenda" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Relatório Venda</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['rFinanceiro'])) {
                                                if ($permissoes['rFinanceiro'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="rFinanceiro" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Relatório Financeiro</span>
                                        </label>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGEleven" data-toggle="collapse">
                                <span><i class='bx bx-cog icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Configurações e Sistema</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGEleven">
                        <div class="widget-content">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                            <input <?php if (isset($permissoes['cUsuario'])) {
                                                if ($permissoes['cUsuario'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="cUsuario" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Configurar Usuário</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['cEmitente'])) {
                                                if ($permissoes['cEmitente'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="cEmitente" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Configurar Emitente</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['cPermissao'])) {
                                                if ($permissoes['cPermissao'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="cPermissao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Configurar Permissão</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php if (isset($permissoes['cBackup'])) {
                                                if ($permissoes['cBackup'] == '1') {
                                                    echo 'checked';
                                                }
                                            }?> name="cBackup" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Backup</span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <input <?php echo (isset($permissoes['cAuditoria']) && $permissoes['cAuditoria'] == 1) ? 'checked' : ''; ?> name="cAuditoria" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Auditoria</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php echo (isset($permissoes['cEmail']) && $permissoes['cEmail'] == 1) ? 'checked' : ''; ?> name="cEmail" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Emails</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input <?php echo (isset($permissoes['cSistema']) && $permissoes['cSistema'] == 1) ? 'checked' : ''; ?> name="cSistema" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Sistema</span>
                                        </label>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                    
                </div>
            </div>
        </div> -->
    </form>
</div>


<script type="text/javascript" src="<?php echo base_url()?>assets/js/validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#marcarTodos").change(function () {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });
        $("#formPermissao").validate({
            rules :{
                nome: {required: true}
            },
            messages:{
                nome: {required: 'Campo obrigatório'}
            }});
    });
</script>
