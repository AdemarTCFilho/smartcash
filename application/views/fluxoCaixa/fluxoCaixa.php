<link rel="stylesheet" href="<?= base_url('application/views/contasPagar/contasPagar.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/fluxoCaixa.css') ?>">

<div class="container">
    <div class="top">
        <div>
            <div class="small">FINANCEIRO</div>
            <div class="title">Fluxo de Caixa</div>
            <div class="sub">Visão consolidada — previsto x realizado, alimentado por lançamentos e baixas</div>
        </div>
        <div class="filters">
            <select id="filtroUnidade" style="width:220px;">
                <option value="">Todas as unidades</option>
            </select>
            <select id="filtroConta" style="width:220px;">
                <option value="">Todas as contas</option>
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
            <div class="label">Saldo bancário atual</div>
            <div class="value azul" id="cardSaldoBancario">R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">Entradas realizadas</div>
            <div class="value green" id="cardEntradas">R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">Saídas realizadas</div>
            <div class="value red" id="cardSaidas">R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">Resultado realizado</div>
            <div class="value green" id="cardResultado">R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">A receber (previsto)</div>
            <div class="value green" id="cardAReceber">R$ 0,00</div>
            <div class="muted" id="cardAReceberQtd">0 título(s)</div>
        </div>
        <div class="card">
            <div class="label">A pagar (previsto)</div>
            <div class="value red" id="cardAPagar">R$ 0,00</div>
            <div class="muted" id="cardAPagarQtd">0 título(s)</div>
        </div>
        <div class="card">
            <div class="label">Inadimplência</div>
            <div class="value yellow" id="cardInadimplencia">R$ 0,00</div>
            <div class="muted" id="cardInadimplenciaQtd">0 título(s)</div>
        </div>
        <div class="card">
            <div class="label">Contas vencidas</div>
            <div class="value yellow" id="cardContasVencidas">0</div>
        </div>
    </div>

    <div class="grid-duplo">
        <div class="card chart-card">
            <div class="texto-card">Entradas vs Saídas (realizado)</div>
            <canvas id="graficoBarras" height="200"></canvas>
        </div>
        <div class="card chart-card">
            <div class="texto-card">Saldo acumulado realizado</div>
            <canvas id="graficoLinha" height="200"></canvas>
        </div>
    </div>

    <div class="panel" style="margin-top:14px;">
        <div class="div-espaco">
            <div class="panel-title">Fluxo de Caixa Diário</div>
            <div class="panel-sub" id="totalRegistros"></div>
        </div>
        <div class="tablewrap">
            <table>
                <thead>
                    <tr>
                        <th>DATA</th>
                        <th>ENTRADAS</th>
                        <th>SAÍDAS</th>
                        <th>SALDO DO DIA</th>
                    </tr>
                </thead>
                <tbody id="tabelaFluxo">
                    <tr><td colspan="4" class="panel-sub-vazio">Sem movimentações no período.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('assets/js/fluxoCaixa.js') ?>"></script>
