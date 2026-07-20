<meta charset="UTF-8" />
<title>Relatórios Financeiros</title>
<link
  href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
  rel="stylesheet"
/>
<style>
  :root {
    --bg: #05081d;
    --panel: #0b1030;
    --border: #202a68;
    --text: #fff;
    --muted: #9aa3d0;
    --green: #00ff9d;
    --red: #ff5b67;
    --yellow: #f3bf3a;
    --purple: #6b63ff;
  }

  body {
    background: var(--bg);
    color: var(--text);
    font-family: "Inter", sans-serif;
  }
  .container {
    padding: 20px;
    max-width: 1600px;
    margin: auto;
  }

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
  .sub {
    color: var(--muted);
    font-size: 13px;
    margin-top: 6px;
  }

  .btn {
    background: #040414 !important;
    border: 1px solid var(--border);
    color: var(--text);
  }
  .btn:hover {
    background: #1c1941 !important;
    border: 1px solid var(--border);
  }

  .filters-panel {
    background: var(--panel);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 16px;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
  }
  .filters-left {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: center;
  }
  .filters-left label {
    font-size: 12px;
    color: var(--text);
    margin-right: 6px;
  }
  select,
  input[type="date"] {
    background: #101641;
    color: #fff;
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 12px;
  }

  .cards-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
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
    background: #0b1030;
  }
  .value {
    font-size: 18px;
    margin: 10px 0;
    font-weight: 600;
  }
  .muted {
    color: var(--muted);
    font-size: 12px;
  }
  .value.green {
    color: var(--green);
  }
  .value.red {
    color: var(--red);
  }
  .value.yellow {
    color: var(--yellow);
  }
  .value.branco {
    color: var(--text);
  }

  .tabs {
    display: flex;
    gap: 8px;
    margin-top: 24px;
    flex-wrap: wrap;
  }
  .tab-btn {
    background: #11183d;
    border: 0px solid var(--border);
    color: #8e98c6;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
  }
  .tab-btn.active {
    background: var(--purple);
    color: #fff;
    border: 1px solid var(--purple);
  }

  .tab-content {
    display: none;
    margin-top: 10px;
    padding: 0px !important;
  }
  .tab-content.active {
    display: block;
  }

  .table-panel {
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
  th,
  td {
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
    padding: 20px;
  }

  .grid-duplo {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }
  .texto-card {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text);
  }

  .panel {
        margin-top: 24px;
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 5px;
    }

  @media (max-width: 1000px) {
    .cards-grid {
      grid-template-columns: 1fr;
    }
    .filters-panel {
      flex-direction: column;
      align-items: flex-start;
    }
    .grid-duplo {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="container">
  <!-- Topo -->
  <div class="top">
    <div>
      <div class="title">Relatórios Financeiros</div>
      <div class="sub">
        Visão gerencial baseada em baixas efetivas (regime de caixa)
      </div>
    </div>
    <button class="btn"><i class="fa fa-download" style="font-size:12px"></i>&nbsp; Exportar CSV</button>
  </div>

  <!-- Filtros -->
  <div class="filters-panel">
    <div class="filters-left">
      <div><label>De:</label><br/><input type="date" value="2026-07-01" style="height: 30px; margin-bottom: 1%;"/></div>
      <div><label>Até:</label><br/><input type="date" value="2026-07-31" style="height: 30px; margin-bottom: 1%;"/></div>
      <div>
        <label>Unidade:</label
        ><br/><select>
          <option>Todas</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Cards resumo -->
  <div class="cards-grid">
    <div class="card">
      <div class="label">Recebido</div>
      <div class="value green">R$ 0,00</div>
    </div>
    <div class="card">
      <div class="label">Pago</div>
      <div class="value red">R$ 0,00</div>
    </div>
    <div class="card">
      <div class="label">Resultado</div>
      <div class="value green">R$ 0,00</div>
      <div class="muted">0.0% margem</div>
    </div>
    <div class="card">
      <div class="label">Inadimplência</div>
      <div class="value yellow">R$ 31.000,00</div>
      <div class="muted">3 títulos vencidos</div>
    </div>
    <div class="card">
      <div class="label">A receber</div>
      <div class="value branco">R$ 31.000,00</div>
    </div>
    <div class="card">
      <div class="label">A pagar</div>
      <div class="value branco">R$ 0,00</div>
    </div>
    <div class="card">
      <div class="label">Pagar vencidas</div>
      <div class="value red">R$ 0,00</div>
      <div class="muted">0 títulos</div>
    </div>
    <div class="card">
      <div class="label">Saldo período</div>
      <div class="value green">R$ 0,00</div>
    </div>
  </div>

  <!-- Abas -->
  <div class="tabs panel">
    <button class="tab-btn active" onclick="openTab('unidade')">
      Por unidade
    </button>
    <button class="tab-btn" onclick="openTab('categoria')">
      Por categoria
    </button>
    <button class="tab-btn" onclick="openTab('forma')">
      Por forma de pagamento
    </button>
    <button class="tab-btn" onclick="openTab('fornecedores')">
      Top fornecedores
    </button>
    <button class="tab-btn" onclick="openTab('clientes')">Top clientes</button>
    <button class="tab-btn" onclick="openTab('inadimplencia')">
      Inadimplência
    </button>
  </div>

  <!-- Conteúdos das abas -->
  <div id="unidade" class="tab-content active">
    <div class="table-panel">
      <table>
        <thead>
          <tr>
            <th>UNIDADE</th>
            <th>RECEITA</th>
            <th>DESPESA</th>
            <th>LUCRO</th>
            <th>MARGEM</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="5" class="panel-sub-vazio">Sem dados.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div id="categoria" class="tab-content">
    <div class="grid-duplo">
      <div class="card">
        <div class="texto-card">Receitas por categoria</div>
        <div class="table-panel">
          <table>
            <thead>
              <tr>
                <th>CATEGORIA</th>
                <th>TOTAL</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="2" class="panel-sub-vazio">Sem dados.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card">
        <div class="texto-card">Despesas por categoria</div>
        <div class="table-panel">
          <table>
            <thead>
              <tr>
                <th>CATEGORIA</th>
                <th>TOTAL</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="2" class="panel-sub-vazio">Sem dados.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div id="forma" class="tab-content">
    <div class="table-panel">
      <table>
        <thead>
          <tr>
            <th>FORMA</th>
            <th>ENTRADAS</th>
            <th>SAÍDAS</th>
            <th>LÍQUIDO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="4" class="panel-sub-vazio">Sem dados.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div id="fornecedores" class="tab-content">
    <div class="table-panel">
      <table>
        <thead>
          <tr>
            <th>FORNECEDOR</th>
            <th>TOTAL PAGO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="2" class="panel-sub-vazio">Sem dados.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div id="clientes" class="tab-content">
    <div class="table-panel">
      <table>
        <thead>
          <tr>
            <th>CLIENTE</th>
            <th>TOTAL RECEBIDO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="2" class="panel-sub-vazio">Sem dados.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div id="inadimplencia" class="tab-content">
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
        <tbody>
          <tr>
            <td>Tets</td>
            <td>19/06/2026</td>
            <td>12 dias</td>
            <td>R$ 1.000,00</td>
          </tr>
          <tr>
            <td>Testando</td>
            <td>19/06/2026</td>
            <td>12 dias</td>
            <td>R$ 10.000,00</td>
          </tr>
          <tr>
            <td>prova</td>
            <td>20/06/2026</td>
            <td>11 dias</td>
            <td>R$ 20.000,00</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  function openTab(tabId) {
    document
      .querySelectorAll(".tab-content")
      .forEach((el) => el.classList.remove("active"));
    document
      .querySelectorAll(".tab-btn")
      .forEach((el) => el.classList.remove("active"));
    document.getElementById(tabId).classList.add("active");
    event.target.classList.add("active");
  }
</script>
