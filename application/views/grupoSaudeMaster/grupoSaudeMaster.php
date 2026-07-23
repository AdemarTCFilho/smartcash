<link rel="stylesheet" href="<?= base_url('application/views/grupoSaudeMaster/grupoSaudeMaster.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container">
    <div class="topbar header">
        <div>
            <div class="subtitle">HOLDING - VISÃO EXECUTIVA</div>
            <div class="title">Grupo Saúde Master</div>
            <div class="desc" id="descGrupo">Carregando...</div>
        </div>
        <div class="filters">
            <select id="filtroEmpresa" style="height:43px;min-width:160px">
                <option value="">Todas as empresas</option>
            </select>
            <button class="btn active" data-period="mensal">Mensal</button>
            <button class="btn" data-period="trimestral">Trimestral</button>
            <button class="btn" data-period="anual">Anual</button>
            <select id="periodo-select" class="btn month-select" style="height:43px"></select>
            <button id="btnExportarCSV" class="btn">CSV</button>
        </div>
    </div>

    <div class="stats">
        <div class="card stat">
            <div class="stat-head">
                <div class="stat-label">Receita Consolidada</div>
                <div class="icon blue"><i class="bi bi-wallet2"></i></div>
            </div>
            <div class="value" id="receitaConsolidada">R$ 0,00</div>
            <div class="small" id="receitaPeriodo">—</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="stat-label">Despesa Consolidada</div>
                <div class="icon red"><i class="bi bi-cash-stack"></i></div>
            </div>
            <div class="value" id="despesaConsolidada">R$ 0,00</div>
            <div class="small" id="despesaPercentual">0.0% da receita</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="stat-label">Lucro Consolidado</div>
                <div class="icon green"><i class="bi bi-graph-up"></i></div>
            </div>
            <div class="value" id="lucroConsolidado">R$ 0,00</div>
            <div class="small" id="lucroMargem">Margem 0%</div>
        </div>
        <div class="card stat">
            <div class="stat-head">
                <div class="stat-label">Unidades Ativas</div>
                <div class="icon blue"><i class="bi bi-building"></i></div>
            </div>
            <div class="value" id="unidadesAtivas">0</div>
            <div class="small" id="empresasControladas">0 empresas controladas</div>
        </div>
    </div>

    <div class="card panel">
        <div class="panel-title">Evolução consolidada - 12 meses</div>
        <div class="panel-sub">Receita, despesa e lucro do grupo</div>
        <div class="chart-large"><canvas id="linha"></canvas></div>
    </div>

    <div class="middle">
        <div class="card panel">
            <div class="panel-title">Receita x Despesa por empresa</div>
            <div class="panel-sub">Comparativo no período selecionado</div>
            <div class="chart-medium"><canvas id="barra"></canvas></div>
        </div>
        <div class="card panel">
            <div class="panel-title">Mix de receita</div>
            <div class="panel-sub">Participação por empresa</div>
            <div class="chart-small"><canvas id="donut"></canvas></div>
        </div>
    </div>

    <div class="card panel table-panel">
        <div class="panel-title">Indicadores por empresa</div>
        <div class="panel-sub" id="indicadoresSub">Composição do Grupo Saúde Master no período</div>
        <table class="table">
            <thead>
                <tr>
                    <th>EMPRESA</th>
                    <th>UNIDADES</th>
                    <th>RECEITA</th>
                    <th>DESPESA</th>
                    <th class="alinha-direta">LUCRO</th>
                    <th class="alinha-direta">MARGEM</th>
                </tr>
            </thead>
            <tbody id="indicadoresTbody"></tbody>
        </table>
    </div>

    <div class="card panel table-panel bottom">
        <div class="panel-title">Top 5 Unidades por Lucro</div>
        <div class="panel-sub">Maiores geradoras de resultado no período</div>
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="topUnidadesTbody">
                <tr><td colspan="4" style="color:#7E89B7;text-align:center;padding:30px">Nenhuma unidade no período.</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>var siteUrl = '<?= site_url() ?>';</script>
<script src="<?= base_url('application/views/grupoSaudeMaster/grupoSaudeMaster.js') ?>"></script>
