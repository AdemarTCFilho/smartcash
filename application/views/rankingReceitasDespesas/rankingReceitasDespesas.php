<link rel="stylesheet" href="<?= base_url('application/views/rankingReceitasDespesas/rankingReceitasDespesas.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container">

    <div class="topbar">
        <div>
            <div class="subtitle">PERFORMANCE FINANCEIRA</div>
            <div class="title">Ranking de Receitas & Despesas</div>
            <div class="desc" id="periodoLabel">—</div>
        </div>

        <div class="filters">
            <button class="btn active" data-period="mensal">Mensal</button>
            <button class="btn" data-period="trimestral">Trimestral</button>
            <button class="btn" data-period="anual">Anual</button>
            <select class="btn" id="periodo-select" style="height:43px;"></select>
            <select class="btn" id="filtroEmpresa" style="height:43px;">
                <option value="">Todas as empresas</option>
            </select>
        </div>
    </div>

    <div class="stats">
        <div class="card stat">
            <div class="stat-head">
                <span class="stat-label">Receitas</span>
                <span class="icon green"><i class="bi bi-arrow-up"></i></span>
            </div>
            <div class="value" id="totalReceitas">R$ 0,00</div>
            <div class="small" id="totalReceitasContas">0 lançamento(s)</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <span class="stat-label">Despesas</span>
                <span class="icon red"><i class="bi bi-arrow-down"></i></span>
            </div>
            <div class="value" id="totalDespesas">R$ 0,00</div>
            <div class="small" id="totalDespesasContas">0 lançamento(s)</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <span class="stat-label">Saldo</span>
                <span class="icon blue"><i class="bi bi-wallet2"></i></span>
            </div>
            <div class="value" id="saldo">R$ 0,00</div>
            <div class="small" id="totalLancamentos">0 registro(s)</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <span class="stat-label">Empresas/Unidades</span>
                <span class="icon yellow"><i class="bi bi-buildings"></i></span>
            </div>
            <div class="value" id="totalRegistros" style="font-size:22px;">0</div>
            <div class="small">com lançamentos</div>
        </div>
    </div>

    <div class="cards-duplo">
        <div class="card panel">
            <div class="panel-title">Comparativo Receitas vs Despesas</div>
            <div class="panel-sub">Por unidade / empresa</div>
            <div class="chart-container" style="margin-top:14px;">
                <canvas id="chartBar"></canvas>
            </div>
        </div>
        <div class="card panel">
            <div class="panel-title">Distribuição do Resultado</div>
            <div class="panel-sub">Saldo por unidade</div>
            <div class="chart-container" style="margin-top:14px;">
                <canvas id="chartDoughnut"></canvas>
            </div>
        </div>
    </div>

    <div class="cards-triplo">
        <div class="card panel">
            <div class="panel-title">Maiores Receitas</div>
            <div class="panel-sub">Top 5 do período</div>
            <div class="rank-list" id="rankingReceitas" style="margin-top:10px;">
                <div class="panel-sub-vazio">Carregando...</div>
            </div>
        </div>
        <div class="card panel">
            <div class="panel-title">Maiores Despesas</div>
            <div class="panel-sub">Top 5 do período</div>
            <div class="rank-list" id="rankingDespesas" style="margin-top:10px;">
                <div class="panel-sub-vazio">Carregando...</div>
            </div>
        </div>
        <div class="card panel">
            <div class="panel-title">Ações</div>
            <div class="panel-sub">Exportar relatório</div>
            <div style="display:flex;flex-direction:column;gap:8px;margin-top:14px;">
                <button class="btn" onclick="exportarCSV()" style="width:100%;text-align:center;">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV
                </button>
                <button class="btn" onclick="exportarPDF()" style="width:100%;text-align:center;">
                    <i class="bi bi-file-earmark-pdf"></i> Exportar PDF (imprimir)
                </button>
                <hr style="border-color:var(--border);margin:8px 0;">
                <div class="view-toggle" style="display:flex;gap:4px;">
                    <button class="btn active" data-visao="unidade" style="flex:1;text-align:center;">Por Unidade</button>
                    <button class="btn" data-visao="empresa" style="flex:1;text-align:center;">Por Empresa</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card panel">
        <div class="panel-title">Detalhamento</div>
        <div class="panel-sub" id="totalRegistrosDetalhe"></div>
        <div class="tablewrap">
            <table>
                <thead>
                    <tr>
                        <th id="thCol1">UNIDADE</th>
                        <th>EMPRESA</th>
                        <th>RECEITA</th>
                        <th>DESPESA</th>
                        <th>SALDO</th>
                    </tr>
                </thead>
                <tbody id="tabelaDados">
                    <tr><td colspan="5" class="panel-sub-vazio">Carregando...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.color = "#7E89B7";
    Chart.defaults.borderColor = "rgba(255,255,255,.05)";
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('application/views/rankingReceitasDespesas/rankingReceitasDespesas.js') ?>"></script>
