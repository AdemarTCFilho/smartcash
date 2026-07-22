<link rel="stylesheet" href="<?= base_url('application/views/contasPagar/contasPagar.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/relatoriosFinanceiros.css') ?>">

<div class="container">
    <div class="top">
        <div>
            <div class="small">FINANCEIRO</div>
            <div class="title">Relatórios Financeiros</div>
            <div class="sub">Visão gerencial baseada em baixas efetivas (regime de caixa)</div>
        </div>
        <div class="filters">
            <select id="filtroUnidade" style="width:250px;">
                <option value="">Todas as unidades</option>
            </select>
        </div>
    </div>

    <div class="linha-btn">
        <button type="button" id="btnExportarCSV" style="background:#11183D;border:1px solid var(--border);color:#8E98C6;">
            <i class="fa fa-download" aria-hidden="true"></i>&nbsp; Exportar CSV
        </button>
    </div>

    <div class="filters-panel">
        <label>De:</label>
        <input type="date" id="filtroDataInicio" style="height:35px;">
        <label>Até:</label>
        <input type="date" id="filtroDataFim" style="height:35px;">
    </div>

    <div class="cards-grid" id="cardsResumo">
        <div class="card">
            <div class="label">Recebido</div>
            <div class="value green" id="cardRecebido">R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">Pago</div>
            <div class="value red" id="cardPago">R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">Resultado</div>
            <div class="value green" id="cardResultado">R$ 0,00</div>
            <div class="muted" id="cardMargem">0,0% margem</div>
        </div>
        <div class="card">
            <div class="label">A Receber</div>
            <div class="value branco" id="cardAReceber">R$ 0,00</div>
            <div class="muted" id="cardAReceberQtd">0 título(s)</div>
        </div>
        <div class="card">
            <div class="label">A Pagar</div>
            <div class="value branco" id="cardAPagar">R$ 0,00</div>
            <div class="muted" id="cardAPagarQtd">0 título(s)</div>
        </div>
        <div class="card">
            <div class="label">Pagar vencidas</div>
            <div class="value red" id="cardPagarVencidas">R$ 0,00</div>
            <div class="muted" id="cardPagarVencidasQtd">0 título(s)</div>
        </div>
        <div class="card">
            <div class="label">Inadimplência</div>
            <div class="value yellow" id="cardInadimplencia">R$ 0,00</div>
            <div class="muted" id="cardInadimplenciaQtd">0 título(s)</div>
        </div>
        <div class="card">
            <div class="label">Saldo período</div>
            <div class="value green" id="cardSaldoPeriodo">R$ 0,00</div>
        </div>
    </div>

    <div class="tabs panel">
        <button class="tab-btn active" onclick="openTab('inadimplencia')">Inadimplência</button>
        <button class="tab-btn" onclick="openTab('contas_receber')">Contas a Receber</button>
        <button class="tab-btn" onclick="openTab('contas_pagar')">Contas a Pagar</button>
        <button class="tab-btn" onclick="openTab('historico_pagamentos')">Histórico de Pagamentos</button>
        <button class="tab-btn" onclick="openTab('historico_recebimentos')">Histórico de Recebimentos</button>
        <button class="tab-btn" onclick="openTab('por_unidade')">Por Unidade</button>
        <button class="tab-btn" onclick="openTab('por_categoria')">Por Categoria</button>
        <button class="tab-btn" onclick="openTab('top_clientes')">Top Clientes</button>
        <button class="tab-btn" onclick="openTab('top_fornecedores')">Top Fornecedores</button>
    </div>

    <div id="tab-inadimplencia" class="tab-content active">
        <div class="table-panel">
            <table>
                <thead>
                    <tr>
                        <th>CLIENTE</th>
                        <th>VENCIMENTO</th>
                        <th>DIAS ATRASO</th>
                        <th>VALOR</th>
                    </tr>
                </thead>
                <tbody id="tabelaInadimplencia">
                    <tr><td colspan="4" class="panel-sub-vazio">Nenhum título vencido.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab-contas_receber" class="tab-content">
        <div class="table-panel">
            <table>
                <thead>
                    <tr>
                        <th>CLIENTE</th>
                        <th>VENCIMENTO</th>
                        <th>VALOR</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody id="tabelaContasReceber">
                    <tr><td colspan="4" class="panel-sub-vazio">Nenhum lançamento encontrado.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab-contas_pagar" class="tab-content">
        <div class="table-panel">
            <table>
                <thead>
                    <tr>
                        <th>CLIENTE</th>
                        <th>VENCIMENTO</th>
                        <th>VALOR</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody id="tabelaContasPagar">
                    <tr><td colspan="4" class="panel-sub-vazio">Nenhum lançamento encontrado.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab-historico_pagamentos" class="tab-content">
        <div class="table-panel">
            <table>
                <thead>
                    <tr>
                        <th>CLIENTE</th>
                        <th>VENCIMENTO</th>
                        <th>VALOR PAGO</th>
                        <th>DATA PAGAMENTO</th>
                    </tr>
                </thead>
                <tbody id="tabelaHistoricoPagamentos">
                    <tr><td colspan="4" class="panel-sub-vazio">Nenhum lançamento encontrado.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab-historico_recebimentos" class="tab-content">
        <div class="table-panel">
            <table>
                <thead>
                    <tr>
                        <th>CLIENTE</th>
                        <th>VENCIMENTO</th>
                        <th>VALOR RECEBIDO</th>
                        <th>DATA RECEBIMENTO</th>
                    </tr>
                </thead>
                <tbody id="tabelaHistoricoRecebimentos">
                    <tr><td colspan="4" class="panel-sub-vazio">Nenhum lançamento encontrado.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab-por_unidade" class="tab-content">
        <div class="table-panel">
            <table>
                <thead>
                    <tr>
                        <th>UNIDADE</th>
                        <th>RECEITA</th>
                        <th>DESPESA</th>
                        <th>RESULTADO</th>
                        <th>MARGEM</th>
                    </tr>
                </thead>
                <tbody id="tabelaPorUnidade">
                    <tr><td colspan="5" class="panel-sub-vazio">Sem dados.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab-por_categoria" class="tab-content">
        <div class="grid-duplo">
            <div class="card">
                <div class="texto-card">Receitas por categoria</div>
                <div class="table-panel">
                    <table>
                        <thead><tr><th>CATEGORIA</th><th>TOTAL</th></tr></thead>
                        <tbody id="tabelaReceitasCategoria">
                            <tr><td colspan="2" class="panel-sub-vazio">Sem dados.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="texto-card">Despesas por categoria</div>
                <div class="table-panel">
                    <table>
                        <thead><tr><th>CATEGORIA</th><th>TOTAL</th></tr></thead>
                        <tbody id="tabelaDespesasCategoria">
                            <tr><td colspan="2" class="panel-sub-vazio">Sem dados.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="tab-top_clientes" class="tab-content">
        <div class="table-panel">
            <table>
                <thead>
                    <tr>
                        <th>CLIENTE</th>
                        <th>TOTAL RECEBIDO</th>
                    </tr>
                </thead>
                <tbody id="tabelaTopClientes">
                    <tr><td colspan="2" class="panel-sub-vazio">Sem dados.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="tab-top_fornecedores" class="tab-content">
        <div class="table-panel">
            <table>
                <thead>
                    <tr>
                        <th>FORNECEDOR</th>
                        <th>TOTAL PAGO</th>
                    </tr>
                </thead>
                <tbody id="tabelaTopFornecedores">
                    <tr><td colspan="2" class="panel-sub-vazio">Sem dados.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('assets/js/relatoriosFinanceiros.js') ?>"></script>
