
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
            padding: 20px;
            max-width: 1600px;
            margin: auto;
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
            background: #5358ee;
            border: 1px solid var(--border);
            color: #fff;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
        }

        .btn:hover { background: #4e46d6; }

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

        .panel-sub-vazio {
            color: var(--muted);
            font-size: 13px;
            text-align: center;
            padding: 15px;
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
                <div class="title">Contas Bancárias</div>
                <div class="desc">
                    Cadastro de contas e saldos consolidados.
                </div>
            </div>

            <div class="filters">
                <button class="btn">+ Lançamento manual</button>
            </div>

        </div>


        <div class="card panel table-panel bottom">

            <div class="panel-sub-vazio">Nenhuma conta cadastrada.</div>

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