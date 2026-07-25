<link rel="stylesheet" href="<?= base_url('application/views/boardExecutivo/boardExecutivo.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="top">
        <div>
            <div class="small">GOVERNANÇA</div>
            <div class="title">Board Executivo — Painel de Segurança</div>
            <div class="sub">Deliberação estratégica baseada em métricas reais</div>
        </div>
        <div class="top-stats">
            <span class="chip"><i class="fa fa-users"></i> <span id="totalExecutivos">5</span> executivos</span>
            <span class="chip"><i class="fa fa-comments"></i> <span id="totalRodadas">3</span> rodadas</span>
        </div>
    </div>

    <div id="painelPauta">
        <div class="panel pauta-panel">
            <h3><i class="fa fa-gavel"></i> Pauta do Board</h3>
            <textarea id="inputPauta" rows="2" placeholder="Ex.: Devemos reforçar bloqueios de IPs suspeitos ou investir em autenticação multifator?">Devemos reforçar bloqueios de IPs suspeitos ou investir em autenticação multifator?</textarea>
            <div class="pauta-actions">
                <button id="btnConvocar" class="btn-primary"><i class="fa fa-bullhorn"></i> Convocar Board</button>
                <button id="btnExportarCSV" class="btn-outline" style="display:none"><i class="fa fa-download"></i> Exportar Transcrição</button>
            </div>
            <div id="indicadoresVivos" class="indicadores-vivos"></div>
        </div>
    </div>

    <div id="painelBoard" style="display:none">
        <div class="board-header">
            <div class="board-meta">
                <span class="board-round" id="rodadaLabel">Parecer Inicial</span>
                <span class="board-status" id="statusLabel">Em andamento...</span>
            </div>
            <div class="board-execs" id="boardExecs"></div>
        </div>

        <div class="board-grid">
            <div class="panel board-transcricao">
                <h3><i class="fa fa-file-text"></i> Transcrição da Reunião</h3>
                <div id="transcricao" class="transcricao-area"></div>
            </div>

            <div class="panel board-resumo">
                <h3><i class="fa fa-tasks"></i> Resumo da Deliberação</h3>
                <div id="resumoArea">
                    <div class="resumo-vazio">Aguardando início da deliberação...</div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel historico-panel" id="painelHistorico">
        <h3><i class="fa fa-history"></i> Histórico de Deliberações</h3>
        <div id="historicoLista"><div style="color:var(--muted);text-align:center;padding:20px">Carregando...</div></div>
    </div>
</div>

<script>var siteUrl = '<?= site_url() ?>';</script>
<script src="<?= base_url('application/views/boardExecutivo/boardExecutivo.js') ?>"></script>
