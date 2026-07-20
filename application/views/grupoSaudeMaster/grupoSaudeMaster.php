
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Grupo Saúde Master</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bg: #05071A;
            --card: #0e0c2b;
            --border: #1B2455;
            --text: #FFFFFF;
            --muted: #7E89B7;
            --green: #42D67A;
            --red: #FF5B6A;
            --blue: #6172FF;
            --yellow: #F4C542;
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
            max-width: 1600px;
            margin: auto;
            padding: 20px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 18px;
        }

        .subtitle {
            color: #5358ee;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .title {
            font-size: 34px;
            font-weight: 700;
            margin-top: 5px;
        }

        .desc {
            color: var(--muted);
            margin-top: 5px;
        }

        .filters {
            display: flex;
            gap: 8px;
        }

        .btn {
            background: #11183D;
            border: 1px solid var(--border);
            color: #8E98C6;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn.active {
            color: #fff;
            background: #5358ee;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .stat {
            padding: 22px;
        }

        .stat-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-label {
            color: #8E98C6;
            font-size: 11px;
            text-transform: uppercase;
        }

        .icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon.blue {
            background: rgba(97, 114, 255, .15);
            color: var(--blue);
        }

        .icon.red {
            background: rgba(255, 91, 106, .15);
            color: var(--red);
        }

        .icon.green {
            background: rgba(66, 214, 122, .15);
            color: var(--green);
        }

        .value {
            margin-top: 18px;
            font-size: 26px;
            font-weight: 700;
            color: var(--text);
        }

        .small {
            margin-top: 8px;
            color: var(--muted);
            font-size: 13px;
        }

        .panel {
            margin-top: 18px;
            padding: 18px;
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

        .chart-large {
            height: 420px;
        }

        .middle {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 16px;
        }

        .chart-medium {
            height: 300px;
        }

        .chart-small {
            height: 300px;
        }

        .table-panel {
            margin-top: 18px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        .table th {
            color: #8E98C6;
            text-align: left;
            padding: 14px;
            font-size: 12px;
            border-bottom: 1px solid var(--border);
        }

        .table td {
            padding: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            color: var(--text);
        }

        .green {
            color: var(--green) !important;
        }

        .bottom {
            margin-top: 18px;
        }

        .rank-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
        }

        .rank-left {
            display: flex;
            gap: 12px;
            align-items: center;
            color: var(--text);
        }

        .rank-number {
            width: 28px;
            height: 28px;
            background: #323d7c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .month-select {
            background: #09102d;
            color: white;
            border: 1px solid var(--border);
            padding: 10px 16px;
            outline: none;
        }

        .alinha-direta {
            text-align: right !important;
        }

        .cor-texto-branco {
            color: var(--text);
        }

        .sub-texto {
            font-size: 9px;
            margin-bottom: -1%;
            color: #7E89B7;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid var(--border);
            padding-bottom: 25px;
        }
        .tamanho-td {
            width: 0px;
        }

        .tamanho-td-empresa {
            width: 70%;
        }

        @media(max-width:1100px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .middle {
                grid-template-columns: 1fr;
            }

        }

        @media(max-width:700px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                gap: 15px;
            }

        }
    </style>

    <div class="container">

        <div class="topbar header">

            <div>
                <div class="subtitle">HOLDING - VISÃO EXECUTIVA</div>
                <div class="title">Grupo Saúde Master</div>
                <div class="desc">
                    Consolidado de 16 unidades • 4 empresas • Jun/26
                </div>
            </div>

            <div class="filters">
                <button class="btn active" data-period="mensal">Mensal</button>
                <button class="btn" data-period="trimestral">Trimestral</button>
                <button class="btn" data-period="anual">Anual</button>
                <select class="btn" id="periodo-select" style="height: 43px;">
                    <option class="btn">Jan/26</option>
                    <option class="btn">Fev/26</option>
                    <option class="btn">Mar/26</option>
                    <option class="btn">Abr/26</option>
                    <option class="btn">Mai/26</option>
                    <option class="btn" selected>Jun/26</option>
                </select>
            </div>

        </div>

        <div class="stats">

            <div class="card stat">
                <div class="stat-head">
                    <div class="stat-label">Receita Consolidada</div>
                    <div class="icon blue"><i class="bi bi-wallet2"></i></div>
                </div>
                <div class="value">R$ 31.000,00</div>
                <div class="small">Jun/26</div>
            </div>

            <div class="card stat">
                <div class="stat-head">
                    <div class="stat-label">Despesa Consolidada</div>
                    <div class="icon red"><i class="bi bi-cash-stack"></i></div>
                </div>
                <div class="value">R$ 0,00</div>
                <div class="small">0.0% da receita</div>
            </div>

            <div class="card stat">
                <div class="stat-head">
                    <div class="stat-label">Lucro Consolidado</div>
                    <div class="icon green"><i class="bi bi-graph-up"></i></div>
                </div>
                <div class="value">R$ 31.000,00</div>
                <div class="small">Margem 100%</div>
            </div>

            <div class="card stat">
                <div class="stat-head">
                    <div class="stat-label">Unidades Ativas</div>
                    <div class="icon blue"><i class="bi bi-building"></i></div>
                </div>
                <div class="value">16</div>
                <div class="small">4 empresas controladas</div>
            </div>

        </div>

        <div class="card panel">
            <div class="panel-title">Evolução consolidada - 12 meses</div>
            <div class="panel-sub">Receita, despesa e lucro do grupo</div>

            <div class="chart-large">
                <canvas id="linha"></canvas>
            </div>
        </div>

        <div class="middle">

            <div class="card panel">
                <div class="panel-title">Receita x Despesa por empresa</div>
                <div class="panel-sub">Comparativo no período selecionado</div>

                <div class="chart-medium">
                    <canvas id="barra"></canvas>
                </div>
            </div>

            <div class="card panel">
                <div class="panel-title">Mix de receita</div>
                <div class="panel-sub">Participação por empresa</div>

                <div class="chart-small">
                    <canvas id="donut"></canvas>
                </div>
            </div>

        </div>

        <div class="card panel table-panel">

            <div class="panel-title">Indicadores por empresa</div>
            <div class="panel-sub">Composição do Grupo Saúde Master no período</div>

            <table class="table">

                <thead>
                    <tr>
                        <th>EMPRESA</th>
                        <th>UNIDADES</th>
                        <th>RECEITA</th>
                        <th>DESPESA</th>
                        <th class="alinha-direta">LUCRO</th>
                        <th style="text-align: right !important;">MARGEM</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><span><i class='fa fa-circle' style="font-size:10px;color:#5358ee;margin-left: -1%;margin-top: 6px;"></i></span>&nbsp;&nbsp;Workmed</td>
                        <td>7</td>
                        <td>R$ 0,00</td>
                        <td>R$ 0,00</td>
                        <td class="green alinha-direta">R$ 0,00</td>
                        <td class="green alinha-direta">0%</td>
                    </tr>

                    <tr>
                        <td><i class='fa fa-circle' style="font-size:10px;color:#ffc31a;margin-left: -1%;margin-top: 6px;"></i></span>&nbsp;&nbsp;Vitalab</td>
                        <td>7</td>
                        <td>R$ 20.000,00</td>
                        <td>R$ 0,00</td>
                        <td class="green alinha-direta">R$ 20.000,00</td>
                        <td class="green alinha-direta">100%</td>
                    </tr>

                    <tr>
                        <td><i class='fa fa-circle' style="font-size:10px;color:#4cd676;margin-left: -1%;margin-top: 6px;"></i></span>&nbsp;&nbsp;Agreste Saúde</td>
                        <td>1</td>
                        <td>R$ 0,00</td>
                        <td>R$ 0,00</td>
                        <td class="green alinha-direta">R$ 0,00</td>
                        <td class="green alinha-direta">0%</td>
                    </tr>

                    <tr>
                        <td><i class='fa fa-circle' style="font-size:10px;color:#ff5453;margin-left: -1%;margin-top: 6px;"></i></span>&nbsp;&nbsp;Plusmed</td>
                        <td>1</td>
                        <td>R$ 11.000,00</td>
                        <td>R$ 0,00</td>
                        <td class="green alinha-direta">R$ 11.000,00</td>
                        <td class="green alinha-direta">100%</td>
                    </tr>
                </tbody>

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

                <tbody>
                    <tr>
                        <td class="tamanho-td"><div class="rank-number">1</div></td>
                        <td class="tamanho-td-empresa"><strong>Vitalab Águas Belas</strong><br/><small class="small"><i class='fa fa-circle' style="font-size:10px;color:#ffc31a;margin-left: -1%;margin-top: 3px;"></i>&nbsp;&nbsp;Vitalab</small></td>
                        <td class="cor-texto-branco alinha-direta"><p class="sub-texto">RECEITA</p>R$ 20.000,00</td>
                        <td class="green alinha-direta"><p class="sub-texto">LUCRO</p>R$ 20.000,00</td>
                    </tr>

                    <tr>
                        <td class="tamanho-td"><div class="rank-number">2</div></td>
                        <td class="tamanho-td-empresa"><strong>Plusmed SUS</strong><br/><small class="small"><i class='fa fa-circle' style="font-size:10px;color:#ff5453;margin-left: -1%;margin-top: 3px;"></i>&nbsp;&nbsp;Plusmed</small></td>
                        <td class="cor-texto-branco alinha-direta"><p class="sub-texto">RECEITA</p>R$ 11.000,00</td>
                        <td class="green alinha-direta"><p class="sub-texto">LUCRO</p>R$ 11.000,00</td>
                    </tr>
                </tbody>

            </table>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        Chart.defaults.color = "#7E89B7";
        Chart.defaults.borderColor = "rgba(255,255,255,.05)";

        new Chart(document.getElementById('linha'), {
            type: 'line',
            data: {
                labels: ['Jul/25', 'Ago/25', 'Set/25', 'Out/25', 'Nov/25', 'Dez/25', 'Jan/26', 'Fev/26', 'Mar/26', 'Abr/26', 'Mai/26', 'Jun/26'],
                datasets: [{
                    label: 'Lucro',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5000, 31000],
                    borderColor: '#6172FF',
                    backgroundColor: 'rgba(97,114,255,.15)',
                    fill: true,
                    tension: .4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart(document.getElementById('barra'), {
            type: 'bar',
            data: {
                labels: ['Workmed', 'Vitalab', 'Agreste', 'Plusmed'],
                datasets: [
                    {
                        label: 'Receita',
                        backgroundColor: '#42D67A',
                        data: [0, 20000, 0, 11000]
                    },
                    {
                        label: 'Lucro',
                        backgroundColor: '#6172FF',
                        data: [0, 20000, 0, 11000]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart(document.getElementById('donut'), {
            type: 'doughnut',
            data: {
                labels: ['Workmed', 'Vitalab', 'Agreste', 'Plusmed'],
                datasets: [{
                    data: [0, 64.5, 0, 35.5],
                    backgroundColor: [
                        '#6172FF',
                        '#F4C542',
                        '#FF5B6A',
                        '#42D67A'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        const filtros = document.querySelectorAll('.filters .btn[data-period]');
        const select = document.getElementById('periodo-select');

        const opcoesMensais = ['Jan/26', 'Fev/26', 'Mar/26', 'Abr/26', 'Mai/26', 'Jun/26',
                               'Jul/26', 'Ago/26', 'Set/26', 'Out/26', 'Nov/26', 'Dez/26'];
        const opcoesTrimestrais = [
            { value: 'T1', label: 'T1 (Jul/25\u2013Set/25)' },
            { value: 'T2', label: 'T2 (Out/25\u2013Dez/25)' },
            { value: 'T3', label: 'T3 (Jan/26\u2013Mar/26)' },
            { value: 'T4', label: 'T4 (Abr/26\u2013Jun/26)' }
        ];

        filtros.forEach(btn => {
            btn.addEventListener('click', () => {
                filtros.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const periodo = btn.dataset.period;

                if (periodo === 'anual') {
                    select.style.display = 'none';
                } else {
                    select.style.display = '';
                    select.innerHTML = '';

                    if (periodo === 'trimestral') {
                        opcoesTrimestrais.forEach(op => {
                            const opt = document.createElement('option');
                            opt.value = op.value;
                            opt.textContent = op.label;
                            opt.classList.add('btn'); 
                            select.appendChild(opt);
                        });
                    } else {
                        opcoesMensais.forEach(op => {
                            const opt = document.createElement('option');
                            opt.textContent = op;
                            opt.classList.add('btn'); 
                            select.appendChild(opt);
                        });
                    }
                    select.selectedIndex = 0;
                }
            });
        });

    </script>