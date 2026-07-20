
<meta charset="UTF-8">
<title>Baixas Financeiras</title>
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
        --purple: #5358ee;
    }

    body {
        background: var(--bg);
        color: var(--text);
        font-family: 'Inter', sans-serif;
    }

    .container {
        padding: 12px;
        max-width: 1600px;
        margin: auto;
    }

    .title {
        font-size: 34px;
        font-weight: 700;
        margin-top: 5px;
    }

    .sub {
        color: var(--muted);
        font-size: 13px;
    }

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
        background: #0b1030;
    }

    .value {
        font-size: 20px;
        margin: 10px 0;
        font-weight: 600;
    }

    .value.red { color: var(--text); }
    .value.green { color: var(--text); }
    .muted { color: var(--muted); font-size: 12px; }

    .panel {
        margin-top: 24px;
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 5px;
    }

    .linha-btn {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn {
        background: #11183D;
        /* border: 1px solid var(--border); */
        color: #8E98C6;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
    }

    .div-center {
        text-align: center !important;
        padding:30px;
    }

    .btn:hover { background: #121f5e; color: #fff; }
    .btn.active { background: var(--purple); color: #fff; border: 1px solid var(--purple); }

    .tab-content { display: none; margin-top: -14px; }
    .tab-content.active { display: block; }

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

    .tab-content:not(.doc-example-content) {
        z-index: 1;
        padding: 0px !important;
    }

    th { color: #ffffff; text-align: left; }
    .status { padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 700; background:#331731; color:#ff7180; }
    .baixar-btn { background: var(--purple); color:#fff; border:none; padding:6px 10px; border-radius:6px; cursor:pointer; font-size:11px; }
</style>

<div class="container">
    <div class="title">Baixas Financeiras</div>
    <div class="sub">Controle de pagamentos e recebimentos · Auditoria</div>

    <div class="cards-duplo">
        <div class="card">
            <div class="label">A PAGAR (SALDO DEVEDOR)</div>
            <div class="value red">R$ 0,00</div>
            <div class="muted">0 título(s)</div>
        </div>
        <div class="card">
            <div class="label">A RECEBER (SALDO)</div>
            <div class="value green">R$ 31.000,00</div>
            <div class="muted">3 título(s)</div>
        </div>
    </div>

    <!-- Abas -->
    <div class="panel">
        <div class="linha-btn">
            <button class="btn active" onclick="openTab('pagar')">Contas a Pagar</button>
            <button class="btn" onclick="openTab('receber')">Contas a Receber</button>
            <button class="btn" onclick="openTab('hist-pag')">Histórico — Pagamentos</button>
            <button class="btn" onclick="openTab('hist-rec')">Histórico — Recebimentos</button>
        </div>
    </div>

    <!-- Conteúdo das abas -->
    <div id="pagar" class="tab-content active">
        <div class="card panel div-center">
            <div class="muted">Nenhum título pendente.</div>
        </div>
    </div>

    <div id="receber" class="tab-content">
        <div class="card panel">
            <table>
                <thead>
                    <tr>
                        <th>CLIENTE</th>
                        <th>VENCIMENTO</th>
                        <th>VALOR</th>
                        <th>PAGO/RECEBIDO</th>
                        <th>SALDO</th>
                        <th>STATUS</th>
                        <th>AÇÃO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tets</td>
                        <td>19/06/2026</td>
                        <td>R$ 1.000,00</td>
                        <td>R$ 0,00</td>
                        <td>R$ 1.000,00</td>
                        <td><span class="status">Aberta</span></td>
                        <td><button class="baixar-btn">Baixar</button></td>
                    </tr>
                    <tr>
                        <td>Testando</td>
                        <td>19/06/2026</td>
                        <td>R$ 10.000,00</td>
                        <td>R$ 0,00</td>
                        <td>R$ 10.000,00</td>
                        <td><span class="status">Aberta</span></td>
                        <td><button class="baixar-btn">Baixar</button></td>
                    </tr>
                    <tr>
                        <td>prova</td>
                        <td>20/06/2026</td>
                        <td>R$ 20.000,00</td>
                        <td>R$ 0,00</td>
                        <td>R$ 20.000,00</td>
                        <td><span class="status">Aberta</span></td>
                        <td><button class="baixar-btn">Baixar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="hist-pag" class="tab-content">
        <div class="card panel div-center">
            <div class="muted">Nenhum pagamento encontrado.</div>
        </div>
    </div>

    <div id="hist-rec" class="tab-content">
        <div class="card panel div-center">
            <div class="muted">Nenhum recebimento encontrado.</div>
        </div>
    </div>
</div>

<script>
    function openTab(tabId) {
        // esconder todos
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.btn').forEach(el => el.classList.remove('active'));
        // mostrar selecionado
        document.getElementById(tabId).classList.add('active');
        event.target.classList.add('active');
    }
</script>

