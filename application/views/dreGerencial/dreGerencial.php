<link rel="stylesheet" href="<?= base_url('application/views/dreGerencial/dreGerencial.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="top">
        <div>
            <div class="small">FINANCEIRO</div>
            <div class="title">DRE Gerencial</div>
            <div class="sub">Regime de competência — alimentado por Contas a Receber e Contas a Pagar</div>
        </div>
        <div class="filters">
            <select id="filtroEmpresa" style="min-width:160px">
                <option value="">Todas as empresas</option>
            </select>
            <select id="filtroUnidade" style="min-width:180px">
                <option value="">Todas as unidades</option>
            </select>
            <select id="periodo-select" style="min-width:160px"></select>
        </div>
    </div>

    <div class="linha-btn">
        <button id="btnExportarCSV" class="btn-outline"><i class="fa fa-download" aria-hidden="true"></i>&nbsp; Exportar CSV</button>
    </div>

    <div class="notice">
        <i class="fa fa-info-circle" aria-hidden="true"></i>
        Os valores abaixo são apurados pela data de vencimento (regime de competência).
        Receitas vêm das contas a receber e despesas das contas a pagar dentro do mês selecionado.
    </div>

    <div class="cards">
        <div class="card">
            <div class="label">Receita Total</div>
            <div class="value branco" id="cardReceita">R$ 0,00</div>
            <div class="muted" id="cardReceitaQtd">0 lançamento(s)</div>
        </div>
        <div class="card">
            <div class="label">Despesa Total</div>
            <div class="value red" id="cardDespesa">R$ 0,00</div>
            <div class="muted" id="cardDespesaQtd">0 lançamento(s)</div>
        </div>
        <div class="card">
            <div class="label">Resultado do Mês</div>
            <div class="value branco" id="cardResultado">R$ 0,00</div>
            <div class="muted" id="cardMargem">Margem 0%</div>
        </div>
    </div>

    <div class="panel" id="estruturaDre">
        <h3>Estrutura — <span id="estruturaMesLabel">---</span></h3>
        <div class="linha-dre empty"><span>Carregando...</span></div>
    </div>

    <div class="grid2">
        <div class="panel">
            <h3>Comparativo</h3>
            <div class="tablewrap">
                <table id="tabelaComparativo">
                    <tr><th>Conta</th><th>Atual</th><th>Anterior</th><th>Δ</th></tr>
                    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:20px">Carregando...</td></tr>
                </table>
            </div>
        </div>
        <div class="panel">
            <h3>Evolução — últimos 12 meses</h3>
            <div class="tablewrap">
                <table id="tabelaEvolucao">
                    <tr><th>Mês</th><th>Receitas</th><th>Despesas</th><th>Resultado</th></tr>
                    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:20px">Carregando...</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>var siteUrl = '<?= site_url() ?>';</script>
<script src="<?= base_url('application/views/dreGerencial/dreGerencial.js') ?>"></script>
