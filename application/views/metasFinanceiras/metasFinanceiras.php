
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Metas Financeiras</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        margin: 0;
        background: #06081b;
        color: #fff;
        font-family: Inter, sans-serif
    }

    .container {
        padding: 12px;
        max-width: 1600px;
        margin: auto
    }

    .top {
        display: flex;
        justify-content: space-between;
        align-items: end;
        border-bottom: 1px solid #29306f;
        padding-bottom: 24px
    }

    .small {
        color: #6170ff;
        letter-spacing: 4px;
        font-size: 11px
    }

    h1 {
        margin: 8px 0;
        font-size: 48px
    }

    .sub {
        color: #aab0d8;
        font-size: 13px
    }

    .controls {
        display: flex;
        gap: 10px
    }

    select,
    button {
        background: #14183c;
        color: #fff;
        border: 1px solid #323a88;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 12px;
    }

    button {
        background: #6366f1;
        font-weight: 700
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin: 30px 0
    }

    .card,
    .panel {
        background: #0b1030;
        border: 1px solid #29306f;
        border-radius: 12px
    }

    .card {
        padding: 22px
    }

    .label {
        font-size: 11px;
        letter-spacing: 3px;
        color: #9ca7e8;
        background: #0b1030 !important;
    }

    .v1 {
        font-size: 20px;
        color: #42ef83;
        margin-top: 14px
    }

    .v2 {
        font-size: 20px;
        color: #ffc82d;
        margin-top: 14px
    }

    .v3 {
        font-size: 20px;
        color: #6671ff;
        margin-top: 14px
    }

    .panel h3 {
        margin: 0;
        padding: 22px;
        border-bottom: 1px solid #29306f
    }

    .panel p {
        margin: -10px 22px 18px;
        color: #aab0d8
    }

    table {
        width: 100%;
        border-collapse: collapse
    }

    th,
    td {
        padding: 14px;
        border-top: 1px solid #232a61;
        font-size: 14px
    }

    th {
        text-align: left;
        color: #aab0d8
    }

    .dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
        background: #5865ff
    }

    .green {
        color: #42ef83
    }

    .red {
        color: #ff5c67
    }

    .edit {
        border: 1px solid #38408c;
        border-radius: 6px;
        padding: 6px 10px;
        display: inline-block;
        cursor: pointer;
    }

    .panel-title {
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--text);
    }

    .panel-sub {
        color: var(--muted);
        font-size: 13px;
    }

    .div-espaco {
        padding: 1.5%;
    }

    .title {
        font-size: 34px;
        font-weight: 700;
        margin-top: 5px;
    }

    .filters {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        margin-bottom: 4%;
    }

    .btn {
        background: #11183D;
        border: 1px solid var(--border);
        color: #8E98C6;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn:hover {
        background: #121f5e;
        border: 1px solid var(--border);
        color: #8E98C6;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
    }

    .linha-btn {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        padding-top: 1%;
    }

    tr:hover {
        background: #151b42
    }
</style>

<div class="container">
    <div class="top">
        <div>
            <div class="small">PLANEJAMENTO</div>
            <div class="title">Metas Financeiras</div>
            <div class="sub">Edite para ajustar meta de receita e teto de despesa. Os valores se refletem em <b>Projetado x Realizado.</b></div>
        </div>
        <div class="filters">
            <select class="btn" id="periodo-select" style="height: 43px;">
                <option class="btn">Jan/26</option>
                <option class="btn">Fev/26</option>
                <option class="btn">Mar/26</option>
                <option class="btn">Abr/26</option>
                <option class="btn">Mai/26</option>
                <option class="btn" selected>Jun/26</option>
            </select>
            <select class="btn" style="height: 43px;">
                <option class="btn">Todas as empresas</option>
            </select>
        </div>
    </div>

    <div class="linha-btn">
        <button>+ Nova meta</button>
    </div>

    <div class="cards">
        <div class="card">
            <div class="label">META DE RECEITA</div>
            <div class="v1">R$ 350.000,00</div>
        </div>
        <div class="card">
            <div class="label">META DE DESPESA (TETO)</div>
            <div class="v2">R$ 130.000,00</div>
        </div>
        <div class="card">
            <div class="label">META DE LUCRO</div>
            <div class="v3">R$ 220.000,00</div>
        </div>
    </div>
    <div class="panel">
        <div class="div-espaco">
            <div class="panel-title">Metas por unidade</div>
            <div class="panel-sub">Edite para ajustar meta de receita e teto de despesa. Os valores se refletem em Projetado x Realizado.</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>UNIDADE</th>
                    <th>EMPRESA</th>
                    <th>META RECEITA</th>
                    <th>META DESPESA</th>
                    <th>META LUCRO</th>
                    <th>REALIZADO RECEITA</th>
                    <th>ATINGIMENTO</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>Workmed Sus</b></td>
                    <td><span class='dot' style='background:#5865ff'></span>Workmed</td>
                    <td>R$ 350.000,00</td>
                    <td>R$ 130.000,00</td>
                    <td class='green'>R$ 220.000,00</td>
                    <td>R$ 0,00</td>
                    <td class='red'>0%</td>
                    <td><span class='edit'>✎</span></td>
                </tr>
                <tr>
                    <td><b>Workmed Garanhuns</b></td>
                    <td><span class='dot' style='background:#5865ff'></span>Workmed</td>
                    <td>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='green'>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='red'>—</td>
                    <td><span class='edit'>✎</span></td>
                </tr>
                <tr>
                    <td><b>Agreste Saúde</b></td>
                    <td><span class='dot' style='background:#42ef83'></span>Agreste Saúde</td>
                    <td>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='green'>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='red'>—</td>
                    <td><span class='edit'>✎</span></td>
                </tr>
                <tr>
                    <td><b>Plusmed Sus</b></td>
                    <td><span class='dot' style='background:#ff6666'></span>Plusmed</td>
                    <td>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='green'>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='red'>—</td>
                    <td><span class='edit'>✎</span></td>
                </tr>
                <tr>
                    <td><b>Vitalab Petrolina</b></td>
                    <td><span class='dot' style='background:#ffc82d'></span>Vitalab</td>
                    <td>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='green'>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='red'>—</td>
                    <td><span class='edit'>✎</span></td>
                </tr>
                <tr>
                    <td><b>Vitalab Angelim</b></td>
                    <td><span class='dot' style='background:#ffc82d'></span>Vitalab</td>
                    <td>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='green'>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='red'>—</td>
                    <td><span class='edit'>✎</span></td>
                </tr>
                <tr>
                    <td><b>Vitalab Garanhuns</b></td>
                    <td><span class='dot' style='background:#ffc82d'></span>Vitalab</td>
                    <td>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='green'>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='red'>—</td>
                    <td><span class='edit'>✎</span></td>
                </tr>
                <tr>
                    <td><b>Workmed Bom Conselho</b></td>
                    <td><span class='dot' style='background:#5865ff'></span>Workmed</td>
                    <td>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='green'>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class='red'>—</td>
                    <td><span class='edit'>✎</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
