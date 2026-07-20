<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
        :root {
            --bg: #020617;
            --panel: #070b22;
            --panel2: #0a1030;
            --border: #1a2353;

            --text: #f8fafc;
            --muted: #5358ee;

            --blue: #5b6cff;
            --green: #4ade80;
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
            min-height: 100vh;
        }

        .container {
            padding: 20px;
            max-width: 1600px;
            margin: auto
        }

        .small-title {
            color: #5358ee;
            text-transform: uppercase;
            letter-spacing: 4px;
            font-size: 11px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid var(--border);
            padding-bottom: 25px;
        }

        .main-title {
            font-size: 68px;
            font-weight: 300;
            line-height: 1;
            margin-top: 10px;
        }

        .subtitle {
            color: var(--muted);
            margin-top: 10px;
            font-size: 14px;
        }

        .month-select {
            background: #09102d;
            color: white;
            border: 1px solid var(--border);
            padding: 10px 16px;
            outline: none;
        }

        .layout {
            display: grid;
            grid-template-columns: 68% 32%;
            gap: 30px;
            margin-top: 40px;
        }

        .cash-card {
            background: #080c28;
            border: 1px solid var(--border);
            min-height: 190px;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cash-label {
            color: #8792c7;
            letter-spacing: 4px;
            font-size: 11px;
        }

        .cash-value {
            font-size: 64px;
            font-weight: 300;
            margin-top: 10px;
        }

        .cash-growth {
            color: var(--green);
            margin-top: 10px;
            font-weight: 600;
        }

        .alerts {
            border-left: 1px solid var(--border);
            padding-left: 28px;
        }

        .alerts h3 {
            font-size: 20px;
            font-weight: 400;
            margin-bottom: 25px;
        }

        .alerts p {
            color: var(--muted);
            margin-bottom: 40px;
        }

        .meta {
            margin-top: 40px;
            width: 90%;
        }

        .meta-title {
            color: #8792c7;
            font-size: 11px;
            letter-spacing: 4px;
        }

        .meta-percent {
            text-align: right;
            font-size: 28px;
            margin: 10px 0;
        }

        .progress {
            width: 100%;
            height: 10px;
            border: 1px solid var(--border);
        }

        .progress-fill {
            width: 0%;
            height: 100%;
            background: var(--blue);
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-top: 40px;
        }

        .metric {
            border-top: 1px solid var(--border);
            padding-top: 15px;
        }

        .metric-title {
            color: #8792c7;
            letter-spacing: 3px;
            font-size: 11px;
        }

        .metric-value {
            font-size: 22px;
            margin-top: 12px;
        }

        .metric-sub {
            color: var(--muted);
            margin-top: 6px;
            font-size: 13px;
        }

        .green {
            color: var(--green);
        }

        .chart-section {
            margin-top: 40px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 24px;
            font-style: italic;
        }

        .projected {
            text-align: right;
        }

        .projected small {
            display: block;
            color: #8792c7;
            letter-spacing: 3px;
        }

        .chart-box {
            background: #080c28;
            border: 1px solid var(--border);
            padding: 25px;
            height: 320px;
        }

        .results {
            margin-top: 50px;
        }

        .results h2 {
            font-weight: 400;
            margin-bottom: 30px;
        }

        .units {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .unit {
            width: 230px;
        }

        .badge {
            display: inline-block;
            background: #062d1a;
            color: #4ade80;
            font-size: 10px;
            padding: 4px 8px;
            margin-bottom: 10px;
        }

        .unit-name {
            color: #8e98c6;
            letter-spacing: 3px;
            font-size: 11px;
        }

        .unit-value {
            margin-top: 15px;
            font-size: 18px;
            font-weight: 600;
        }

        .bar {
            margin-top: 15px;
            height: 4px;
            background: #162045;
        }

        .bar-fill {
            height: 100%;
            background: #4ade80;
        }

        .footer {
            margin-top: 60px;
            border-top: 1px solid var(--border);
            padding-top: 30px;

            display: flex;
            justify-content: space-between;

            color: #7c87b8;
            font-size: 11px;
            letter-spacing: 3px;
        }

        @media(max-width:1200px) {

            .layout {
                grid-template-columns: 1fr;
            }

            .alerts {
                border-left: none;
                padding-left: 0;
            }

        }
    </style>

<div class="container">

        <div class="header">

            <div>
                <div class="small-title">VISÃO CONSOLIDADA — GRUPO</div>

                <div class="main-title">
                    Dashboard Executivo
                </div>

                <div class="subtitle">
                    16 unidades • 4 empresas • BRL • atualizado agora
                </div>
            </div>

        </div>

        <div class="layout">

            <div>

                <div class="cash-card">

                    <div>
                        <div class="cash-label">SALDO EM CAIXA</div>

                        <div class="cash-value">
                            R$ 0,00
                        </div>

                        <div class="cash-growth">
                            ↗ +0.0% vs mês anterior
                        </div>
                    </div>

                    <div style="width:220px;height:80px;">
                        <canvas id="miniChart"></canvas>
                    </div>

                </div>

                <div class="metrics">

                    <div class="metric">
                        <div class="metric-title">A PAGAR HOJE</div>
                        <div class="metric-value">R$ 0,00</div>
                        <div class="metric-sub">0 compromissos</div>
                    </div>

                    <div class="metric">
                        <div class="metric-title">LUCRO LÍQUIDO</div>
                        <div class="metric-value green">R$ 31.000,00</div>
                        <div class="metric-sub">Margem 100%</div>
                    </div>

                    <div class="metric">
                        <div class="metric-title">INADIMPLÊNCIA</div>
                        <div class="metric-value green">0%</div>
                        <div class="metric-sub">Sobre carteira a receber</div>
                    </div>

                </div>

                <div class="chart-section">

                    <div class="chart-header">

                        <div class="chart-title">
                            Evolução do Faturamento
                        </div>

                        <div class="projected">
                            <small>PROJETADO 30D</small>
                            <strong>R$ 31.000,00</strong>
                        </div>

                    </div>

                    <div class="chart-box">
                        <canvas id="revenueChart"></canvas>
                    </div>

                </div>

                <div class="results">

                    <h2>Resultado por unidade · mês atual</h2>

                    <div class="units">

                        <div class="unit">
                            <div class="badge">SAUDÁVEL</div>
                            <div class="unit-name">VL ÁGUAS BELAS</div>
                            <div class="unit-value">R$ 20.000,00</div>

                            <div class="bar">
                                <div class="bar-fill" style="width:100%"></div>
                            </div>
                        </div>

                        <div class="unit">
                            <div class="badge">SAUDÁVEL</div>
                            <div class="unit-name">PLUSMED</div>
                            <div class="unit-value">R$ 11.000,00</div>

                            <div class="bar">
                                <div class="bar-fill" style="width:55%"></div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="alerts">

                <h3>Alertas Críticos</h3>

                <p>
                    Sem recebíveis vencidos. Tudo em ordem.
                </p>

                <div class="meta">

                    <div class="meta-title">
                        ATINGIMENTO DA META (MÊS)
                    </div>

                    <div class="meta-percent">
                        0%
                    </div>

                    <div class="progress">
                        <div class="progress-fill"></div>
                    </div>

                    <div style="margin-top:15px;text-align:center;color:#7c87b8;font-size:12px;">
                        R$ 31.000,00 DE R$ 0,00
                        <br>
                        Meta = média 3 meses anteriores +5%
                    </div>

                </div>

            </div>

        </div>

        <div class="footer">
            <div>SMART CASH © 2026</div>
            <div>DADOS EM TEMPO REAL · CONTAS A PAGAR E RECEBER</div>
        </div>

    </div>

    <script>

        new Chart(document.getElementById('miniChart'), {
            type: 'line',
            data: {
                labels: ['', '', '', '', ''],
                datasets: [{
                    data: [0, 0, 0, 0, 1],
                    borderColor: '#5b6cff',
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: .4
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { x: { display: false }, y: { display: false } },
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: ['Jul/25', 'Ago/25', 'Set/25', 'Out/25', 'Nov/25', 'Dez/25', 'Jan/26', 'Fev/26', 'Mar/26', 'Abr/26', 'Mai/26', 'Jun/26'],
                datasets: [{
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5000, 31000],
                    borderColor: '#5b6cff',
                    backgroundColor: 'rgba(91,108,255,.10)',
                    fill: true,
                    pointRadius: 0,
                    tension: .4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255,255,255,.08)',
                            borderDash: [4, 4]
                        }
                    }
                }
            }
        });

    </script>