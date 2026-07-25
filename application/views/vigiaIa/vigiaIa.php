<link rel="stylesheet" href="<?= base_url('application/views/vigiaIa/vigiaIa.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="top">
        <div>
            <div class="small">SEGURANÇA</div>
            <div class="title">Vigia IA — Painel de Segurança</div>
            <div class="sub">Monitoramento em tempo real</div>
        </div>
        <div class="filters">
            <select id="periodo-select">
                <option value="7">Últimos 7 dias</option>
                <option value="30">Últimos 30 dias</option>
                <option value="custom">Personalizado</option>
            </select>
            <input type="date" id="data-inicio" style="display:none">
            <input type="date" id="data-fim" style="display:none">
            <button id="btnExportarCSV" class="btn-outline"><i class="fa fa-download"></i> CSV</button>
        </div>
    </div>

    <div class="status-bar">
        <div class="status-item">
            <span class="status-dot green"></span>
            <span class="status-label">Sistema operacional</span>
        </div>
        <div class="status-item" id="auto-status">
            <span class="status-dot green"></span>
            <span class="status-label">Auto-atualização: <span id="auto-label">30s</span></span>
        </div>
    </div>

    <div class="cards">
        <div class="card card-danger">
            <div class="card-icon"><i class="fa fa-shield"></i></div>
            <div class="card-info">
                <div class="label">Tentativas Bloqueadas</div>
                <div class="value" id="cardTentativas">—</div>
                <div class="muted">no período</div>
            </div>
        </div>
        <div class="card card-info">
            <div class="card-icon"><i class="fa fa-user-plus"></i></div>
            <div class="card-info">
                <div class="label">Novos Cadastros</div>
                <div class="value" id="cardCadastros">—</div>
                <div class="muted">usuários criados</div>
            </div>
        </div>
        <div class="card card-warning">
            <div class="card-icon"><i class="fa fa-exclamation-triangle"></i></div>
            <div class="card-info">
                <div class="label">Acessos Suspeitos</div>
                <div class="value" id="cardSuspeitos">—</div>
                <div class="muted">comportamentos anômalos</div>
            </div>
        </div>
        <div class="card card-success">
            <div class="card-icon"><i class="fa fa-globe"></i></div>
            <div class="card-info">
                <div class="label">Países que Acessaram</div>
                <div class="value" id="cardPaises">—</div>
                <div class="muted">quantidade distinta</div>
            </div>
        </div>
    </div>

    <div class="grid2">
        <div class="panel panel-grafico">
            <h3><i class="fa fa-bar-chart"></i> Logins por dia</h3>
            <div id="graficoLogins" style="height:280px"></div>
        </div>
        <div class="panel">
            <h3><i class="fa fa-trophy"></i> Top 5 Países</h3>
            <table class="table-mini" id="tabelaPaises">
                <tr><th>País</th><th>Acessos</th></tr>
                <tr><td colspan="2" style="color:var(--muted);text-align:center;padding:20px">Carregando...</td></tr>
            </table>
        </div>
    </div>

    <div class="grid3">
        <div class="panel">
            <h3><i class="fa fa-ban"></i> IPs com mais falhas</h3>
            <div class="tablewrap">
                <table class="table-mini" id="tabelaIpsFalhas">
                    <tr><th>IP</th><th>País</th><th>Tentativas</th></tr>
                    <tr><td colspan="3" style="color:var(--muted);text-align:center;padding:20px">Carregando...</td></tr>
                </table>
            </div>
        </div>
        <div class="panel">
            <h3><i class="fa fa-bell"></i> Alertas Críticos</h3>
            <div id="listaAlertas"></div>
        </div>
        <div class="panel">
            <h3><i class="fa fa-history"></i> Últimos Logins</h3>
            <div id="listaUltimosLogins"></div>
        </div>
    </div>

    <div class="panel" style="margin-top:20px">
        <h3><i class="fa fa-lock"></i> IPs Bloqueados</h3>
        <div class="tablewrap">
            <table class="table-mini" id="tabelaBloqueados">
                <tr><th>IP</th><th>País</th><th>Motivo</th><th>Bloqueado em</th></tr>
                <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:20px">Carregando...</td></tr>
            </table>
        </div>
    </div>
</div>

<script>var siteUrl = '<?= site_url() ?>';</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= base_url('application/views/vigiaIa/vigiaIa.js') ?>"></script>
