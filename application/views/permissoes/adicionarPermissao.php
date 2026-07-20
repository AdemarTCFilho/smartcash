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
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-family: sans-serif;
    font-weight: bold;
    list-style: none;
  }

  summary::-webkit-details-marker {
    display: none;
  }

  summary::after {
    content: "▼";
    font-size: 12px;
    transition: transform 0.2s ease;
    color: #fff;
  }

  details[open] summary::after {
    transform: rotate(180deg);
  }

  details p {
    font-family: sans-serif;
    margin-top: 10px;
    color: #444;
  }

</style>

<div class="span12" style="margin-left: 0">
    <form action="<?php echo base_url(); ?>index.php/permissoes/adicionar" id="formPermissao" method="post">
        <div class="span12" style="margin-left: 0">
            <div class="widget-box">
                <div class="widget-title">
               <span class="icon">
               <i class="fas fa-lock"></i>
               </span>
                    <h5 style="padding:12px;padding-left:18px!important;margin:-10px 0 0!important;font-size:1.7em;color: var(--title);">Cadastro de Permissão</h5>
                </div>
                <div class="widget-content">
                    <div class="span4">
                        <label style="color: var(--title);">Nome da Permissão</label>
                        <input name="nome" type="text" id="nome" class="span12" value="" />
                    </div>
                    <div class="span3">
                        <label style="color: var(--title);">Situação</label>
                        <select name="situacao" id="situacao" class="span12">
                            <option value="1" selected>Ativo</option>
                            <option value="0">Inativo</option>
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
                                            <input name="vGrupoSaudeMaster" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Grupo Saúde Master</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aGrupoSaudeMaster" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Grupo Saúde Master</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eGrupoSaudeMaster" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Grupo Saúde Master</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dGrupoSaudeMaster" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vProjetadoRealizado" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Projetado x Realizado</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aProjetadoRealizado" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Projetado x Realizado</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eProjetadoRealizado" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Projetado x Realizado</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dProjetadoRealizado" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vMetasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Metas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aMetasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Metas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eMetasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Metas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dMetasFinanceiras" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vRankingReceitasDespesas" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);">Visualizar Ranking de Receitas & Despesas</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aRankingReceitasDespesas" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);">Adicionar Ranking de Receitas & Despesas</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eRankingReceitasDespesas" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);">Editar Ranking de Receitas & Despesas</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dRankingReceitasDespesas" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vContasReceber" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Contas a Receber</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aContasReceber" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Contas a Receber</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eContasReceber" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Contas a Receber</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dContasReceber" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vContasPagar" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Contas a Pagar</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aContasPagar" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Contas a Pagar</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eContasPagar" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Contas a Pagar</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dContasPagar" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vBaixasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Baixas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aBaixasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Baixas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eBaixasFinanceiras" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Baixas Financeiras</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dBaixasFinanceiras" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vConciliacaoBancaria" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Conciliação Bancária</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aConciliacaoBancaria" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Conciliação Bancária</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eConciliacaoBancaria" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Conciliação Bancária</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dConciliacaoBancaria" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vContasBancarias" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Contas Bancárias</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aContasBancarias" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Contas Bancárias</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eContasBancarias" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Contas Bancárias</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dContasBancarias" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vRelatoriosFinanceiros" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Relatórios Financeiros</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aRelatoriosFinanceiros" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Relatórios Financeiros</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eRelatoriosFinanceiros" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Relatórios Financeiros</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dRelatoriosFinanceiros" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vFluxoCaixa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Fluxo de Caixa</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aFluxoCaixa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Fluxo de Caixa</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eFluxoCaixa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Fluxo de Caixa</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dFluxoCaixa" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar DRE Gerencial</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar DRE Gerencial</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eDreGerencial" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar DRE Gerencial</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dDreGerencial" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vBoardExecutivo" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Board Executivo</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aBoardExecutivo" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Board Executivo</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eBoardExecutivo" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Board Executivo</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dBoardExecutivo" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vVigiaIa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Visualizar Vigia IA</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="aVigiaIa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Adicionar Vigia IA</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="eVigiaIa" class="marcar" type="checkbox" value="1" />
                                                <span class="lbl" style="color: var(--title);"> Editar Vigia IA</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input name="dVigiaIa" class="marcar" type="checkbox" value="1" />
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
                                            <input name="vEmpresasUnidades" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Visualizar Empresas / Unidades</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="aEmpresasUnidades" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Adicionar Empresas / Unidades</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="eEmpresasUnidades" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl" style="color: var(--title);"> Editar Empresas / Unidades</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="dEmpresasUnidades" class="marcar" type="checkbox" value="1" />
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
                                            <input name="cUsuario" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Configurar Usuário</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="cEmitente" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Configurar Emitente</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="cPermissao" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Configurar Permissão</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="cBackup" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Backup</span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>
                                            <input name="cAuditoria" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Auditoria</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="cEmail" class="marcar" type="checkbox" value="1" />
                                            <span class="lbl"> Emails</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input name="cSistema" class="marcar" type="checkbox" value="1" />
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
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#marcarTodos").change(function() {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });
        $("#formPermissao").validate({
            rules: {
                nome: {
                    required: true
                }
            },
            messages: {
                nome: {
                    required: 'Campo obrigatório'
                }
            }
        });
    });
</script>
