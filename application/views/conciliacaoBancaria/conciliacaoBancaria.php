
  <meta charset="UTF-8">
  <title>Conciliação Bancária</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #05081d;
      --panel: #0b1030;
      --border: #202a68;
      --text: #fff;
      --muted: #9aa3d0;
      --green: #00ff9d;
      --red: #ff5b67;
      --purple: #6b63ff;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Inter', sans-serif;
    }

    .container {
      padding: 20px;
      max-width: 1600px;
      margin: auto;
    }

    /* Topo com título e botão */
    .top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .title {
      font-size: 32px;
      font-weight: 700;
    }

    .btn {
      background: var(--purple);
      border: none;
      color: #fff;
      padding: 8px 14px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 12px;
      font-weight: 600;
    }

    .btn:hover { background: #4e46d6; }

    .sub {
      color: var(--muted);
      font-size: 13px;
      margin-bottom: 20px;
    }

    /* Painel de filtros */
    .filters-panel {
      background: var(--panel);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 16px;
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      align-items: center;
    }

    .filters-panel label {
      font-size: 12px;
      color: var(--text);
      margin-right: 6px;
    }

    select, input[type="date"], input[type="checkbox"] {
      background: #101641;
      color: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 6px 10px;
      font-size: 12px;
    }

    /* Cards resumo */
    .cards-duplo {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 14px;
      margin-top: 24px;
    }

    .card {
      background: var(--panel);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 18px;
    }

    .label {
      font-size: 11px;
      letter-spacing: 3px;
      color: #95a0d7;
      background: #0b1030 !important;
    }

    .value {
      font-size: 20px;
      margin: 10px 0;
      font-weight: 600;
    }

    .value.green { color: var(--green); }
    .value.red { color: var(--red); }

    /* Tabela */
    .table-panel {
      margin-top: 24px;
      background: var(--panel);
      border: 1px solid var(--border);
      border-radius: 8px;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #1a2257;
      white-space: nowrap;
    }

    th {
      color: #9aa3d0;
      text-align: left;
      background: #0d1338;
    }

    .panel-sub-vazio {
      color: var(--muted);
      font-size: 13px;
      text-align: center;
      padding: 30px;
    }

    .texto-alinhado {
        text-align: right;
    }
    .texto-alinhado-container {
        display: flex;
        gap: 20px;
        margin-left: auto;
    }

    .cor-red {
        color: var(--red) !important;
    }

    .cor-green {
        color: var(--green) !important;
    }

    @media(max-width:1000px) {
      .top { flex-direction: column; align-items: flex-start; gap: 12px; }
      .filters-panel { flex-direction: column; align-items: flex-start; }
      .cards-duplo { grid-template-columns: 1fr; }
    }
  </style>

  <div class="container">
    <!-- Topo -->
    <div class="top">
      <div class="title">Conciliação Bancária</div>
      <button class="btn">+ Lançamento manual</button>
    </div>
    <div class="sub">Comparação de lançamentos com extrato bancário</div>

    <!-- Filtros -->
    <div class="filters-panel">
      <div>
        <label>Conta:</label><br/>
        <select>
          <option>Selecione a conta</option>
        </select>
      </div>
      <div>
        <label>De:</label><br/>
        <input type="date" value="2026-07-01" style="height: 30px; margin-bottom: 1%;">
      </div>
      <div>
        <label>Até:</label><br/>
        <input type="date" value="2026-07-31" style="height: 30px; margin-bottom: 1%;">
      </div>
      <div>
        <label><input type="checkbox"> Somente não conciliadas</label>
      </div>
        <div class="texto-alinhado-container">
        <div class="texto-alinhado">
            <label>Entradas</label><br/>
            <label class="cor-green">R$ 0,00</label>
        </div>
        <div class="texto-alinhado">
            <label>Saídas</label><br/>
            <label class="cor-red">R$ 0,00</label>
        </div>
    </div>
    </div>

    <!-- Tabela -->
    <div class="table-panel">
      <table>
        <thead>
          <tr>
            <th>DATA</th>
            <th>CONTA</th>
            <th>DESCRIÇÃO</th>
            <th>ORIGEM</th>
            <th>VALOR</th>
            <th>CONCILIADA</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="6" class="panel-sub-vazio">Nenhuma movimentação no período.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

