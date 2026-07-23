<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/painel.css" />

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container">
    <div class="header">
        <div>
            <div class="small-title">VISÃO CONSOLIDADA — GRUPO</div>
            <div class="main-title">Dashboard Executivo</div>
            <div class="subtitle" id="infosGrupo">— • — • BRL • atualizado agora</div>
        </div>
        <div class="filter-group">
            <select id="filtroPeriodo">
                <option value="mensal">Mensal</option>
                <option value="trimestral">Trimestral</option>
                <option value="anual">Anual</option>
            </select>
            <button id="btnExportarCSV" class="btn-export">Exportar CSV</button>
        </div>
    </div>

    <div class="layout">
        <div class="main-col">
            <div class="cash-card">
                <div>
                    <div class="cash-label">SALDO EM CAIXA</div>
                    <div class="cash-value" id="saldoCaixa">R$ 0,00</div>
                    <div class="cash-growth" id="saldoVariacao">↗ +0.0% vs mês anterior</div>
                </div>
                <div class="mini-chart-wrap">
                    <canvas id="miniChart"></canvas>
                </div>
            </div>

            <div class="metrics">
                <div class="metric">
                    <div class="metric-title">A PAGAR HOJE</div>
                    <div class="metric-value" id="aPagarHoje">R$ 0,00</div>
                    <div class="metric-sub" id="aPagarHojeQtd">0 compromissos</div>
                </div>
                <div class="metric">
                    <div class="metric-title">LUCRO LÍQUIDO</div>
                    <div class="metric-value" id="lucroLiquido">R$ 0,00</div>
                    <div class="metric-sub" id="lucroMargem">Margem 0%</div>
                </div>
                <div class="metric">
                    <div class="metric-title">INADIMPLÊNCIA</div>
                    <div class="metric-value" id="inadimplencia">0%</div>
                    <div class="metric-sub" id="inadimplenciaQtd">Sobre carteira a receber</div>
                </div>
            </div>

            <div class="chart-section">
                <div class="chart-header">
                    <div class="chart-title">Evolução do Faturamento</div>
                    <div class="projected">
                        <small>PROJETADO 30D</small>
                        <strong id="projetado30D">R$ 0,00</strong>
                    </div>
                </div>
                <div class="chart-box">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="results">
                <h2>Resultado por unidade · mês atual</h2>
                <div class="units" id="resultadoUnidades">
                    <div class="unit empty">Nenhum lançamento no mês atual.</div>
                </div>
            </div>
        </div>

        <div class="alerts">
            <h3>Alertas Críticos</h3>
            <div id="alertasLista">
                <p class="no-alerts">Sem recebíveis vencidos. Tudo em ordem.</p>
            </div>

            <div class="meta">
                <div class="meta-title">ATINGIMENTO DA META (MÊS)</div>
                <div class="meta-percent" id="metaPercentual">0%</div>
                <div class="progress"><div class="progress-fill" id="metaBar" style="width:0%"></div></div>
                <div class="meta-detail" id="metaDetalhe">
                    R$ 0,00 DE R$ 0,00<br>Meta = média 3 meses anteriores +5%
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div>SMART CASH © 2026</div>
        <div>DADOS EM TEMPO REAL · CONTAS A PAGAR E RECEBER</div>
    </div>
</div>

<script src="<?= base_url(); ?>assets/js/painel.js"></script>
