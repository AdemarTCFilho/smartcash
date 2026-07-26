<link rel="stylesheet" href="<?= base_url('application/views/boardExecutivo/boardExecutivo.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
  <div class="header">
    <div>
      <div class="header-badge">SMART CASH — CONSELHO IA</div>
      <div class="header-title">Board Executivo</div>
      <div class="header-sub">Cinco agentes executivos deliberam em três rodadas (parecer inicial, réplica e decisão final do CEO) usando as métricas reais do seu dashboard.</div>
    </div>
    <div class="header-stats">
      <span class="stat-chip"><i class="fa fa-users"></i> <b id="totalExecutivos">5</b> executivos</span>
      <span class="stat-chip"><i class="fa fa-comments"></i> <b>3</b> rodadas</span>
    </div>
  </div>

  <div class="section pauta-section">
    <div class="section-label"><i class="fa fa-gavel"></i> Pauta do Board</div>
    <textarea id="inputPauta" rows="2" placeholder="Ex: Devemos expandir Vitalab para mais uma cidade ou consolidar as unidades atuais?"></textarea>
    <div class="pauta-row">
      <button id="btnConvocar" class="btn-convocar"><i class="fa fa-bullhorn"></i> Convocar Board</button>
      <button id="btnExportarCSV" class="btn-csv" style="display:none"><i class="fa fa-download"></i> Exportar CSV</button>
    </div>
    <div id="indicadoresVivos" class="metricas-bar"></div>
  </div><br>

  <div class="board-bar" id="painelBoardBar" style="display:none">
    <div class="board-bar-left">
      <span class="board-round" id="rodadaLabel">Parecer Inicial</span>
      <span class="board-status" id="statusLabel">Deliberando...</span>
    </div>
  </div>

  <div class="exec-cards" id="execCards"></div>

  <div id="painelTranscricao" style="display:none">
    <div class="transcricao-section">
      <div class="section-label"><i class="fa fa-file-text"></i> Transcrição da Reunião</div>
      <div id="transcricao" class="transcricao-box">
        <div class="transcricao-empty">
          <i class="fa fa-comments-o" style="font-size:40px;display:block;margin-bottom:12px;color:var(--muted)"></i>
          Digite a pauta e clique em <strong>Convocar Board</strong> para iniciar a deliberação.<br>
          Cada executivo dará seu parecer com base nas métricas reais do dashboard.
        </div>
      </div>
    </div>

    <div id="decisaoFinalArea" style="display:none"></div>
  </div>

  <div class="section historico-section">
    <div class="section-label"><i class="fa fa-history"></i> Histórico de Deliberações</div>
    <div id="historicoLista"><div class="empty-state">Carregando...</div></div>
  </div>
</div>

<script>var siteUrl = '<?= site_url() ?>';</script>
<script src="<?= base_url('application/views/boardExecutivo/boardExecutivo.js') ?>"></script>
