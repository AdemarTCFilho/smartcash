    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #05081d;
            --panel: #0b1030;
            --border: #202a68;
            --text: #fff;
            --muted: #9aa3d0;
            --green: #ffffff;
            --red: #ff5b67;
            --yellow: #f3bf3a;
            --purple: #6b63ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            /* padding: 12px; */
        }

        .container {
            padding: 12px;
            max-width: 1600px;
            margin: auto
        }

        .edit {
            border: 1px solid #38408c;
            border-radius: 6px;
            padding: 6px 10px;
            display: inline-block;
            cursor: pointer;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid var(--border);
            padding-bottom: 18px
        }

        .small {
            color: #6170ff;
            letter-spacing: 4px;
            font-size: 11px
        }

        h1 {
            margin: 6px 0;
            font-size: 42px
        }

        .sub {
            color: var(--muted);
            font-size: 13px
        }

        .filters {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .linha-btn {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            padding-top: 1%;
        }

        select,
        button {
            background: #101641;
            color: #fff;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 12px;
        }

        button {
            background: var(--purple);
            font-weight: 600
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-top: 24px
        }

        .cards-duplo {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-top: 24px
        }

        .card,
        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px
        }

        .card {
            padding: 18px
        }

        .label {
            font-size: 11px;
            letter-spacing: 3px;
            color: #95a0d7;
            background: #0b1030 !important;
        }

        .value {
            font-size: 18px;
            margin: 10px 0;
            color: var(--green)
        }

        .texto-card {
            color: var(--green);
            margin: 12px 0;
            font-size: 12px
        }

        .red {
            color: var(--red)
        }

        .muted {
            color: var(--muted);
            font-size: 12px
        }

        .panel {
            margin-top: 24px
        }

        .panel h5 {
            margin: 0;
            padding: 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text) !important;
        }

        .grid4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr)
        }

        .company {
            padding: 16px;
            border-right: 1px solid var(--border)
        }

        .company:last-child {
            border-right: none
        }

        .bar {
            height: 6px;
            background: #161635;
            margin: 10px 0
        }

        .bar div {
            height: 100%;
            background: var(--green);
            width: 0%
        }

        .tablewrap {
            overflow: auto
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #1a2257;
            white-space: nowrap
        }

        th {
            color: #9aa3d0;
            text-align: left
        }

        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            background: #18204d;
            color: #9aa3d0;
            font-size: 10px
        }

        .status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700
        }

        .att {
            background: #4a3900;
            color: #ffd04d;
            border: 0.1px solid #ffd04d;
        }

        .crit {
            background: #331731;
            color: #ff7180;
            border: 0.1px solid #ff7180;
        }

        .green {
            color: var(--green);
            font-size: 12px;
        }

        .title {
            font-size: 34px;
            font-weight: 700;
            margin-top: 5px;
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

        .cards-duplo {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 colunas */
            gap: 14px;
            margin-top: 24px;
        }

        .cards-duplo .receitas {
            grid-column: span 2; /* ocupa 2 colunas */
        }

        .cards-duplo .vencimentos {
            grid-column: span 1; /* ocupa 1 coluna */
        }

        .btn-importar {
            background: #040414 !important;
            border: 1px solid var(--border);
        }

        .btn-importar:hover {
            background: #1c1941 !important;
            border: 1px solid var(--border);
        }

        .div-center {
            text-align: center;
        }

        @media(max-width:1000px) {

            .cards,
            .grid4 {
                grid-template-columns: 1fr
            }
        }
    </style>

    <div class="container">
        <div class="top">
            <div>
                <div class="title">Contas a Pagar</div>
                <div class="sub">Lançamentos reais do banco · 0 registro(s)</div>
            </div>
            <div class="filters">
                <button class="btn-importar"><i class="fa fa-upload" aria-hidden="true" style="font-size:12px"></i>&nbsp; Importar CSV</button><button><i class="fa fa-plus" style="font-size:12px"></i>&nbsp; Nova projeção</button>
            </div>
        </div>

        <div class="cards">
            <div class="card">
                <div class="label">EM ABERTO</div>
                <div class="value">R$ 0,00</div>
                <div class="muted">0 conta(s)</div>
            </div>
            <div class="card">
                <div class="label">PAGAS (HISTÓRICO)</div>
                <div class="value">R$ 0,00</div>
                <div class="muted">0 conta(s)</div>
            </div>
            <div class="card">
                <div class="label">PRÓXIMO VENCIMENTO</div>
                <div class="value">-</div>
            </div>
        </div>

        <div class="cards-duplo">
            <div class="card receitas">
                <div class="texto-card">Despesas por categoria</div>
                <div class="muted">Sem lançamentos ainda.</div>
            </div>
            <div class="card vencimentos">
                <div class="texto-card">Próximos vencimentos</div>
                <div class="muted">Nenhuma conta em aberto.</div>
            </div>
        </div>

        <div class="card panel table-panel bottom">
            <div class="texto-card">Lançamentos</div><hr/>
            <div class="div-center">
                <div class="panel-sub-vazio">Nenhuma conta cadastrada ainda.</div><br/>
                <div class="panel-sub-vazio"><button><i class="fa fa-plus" style="font-size:12px"></i>&nbsp;  Lançar primeira conta</button></div>
            </div>
        </div>

    </div>