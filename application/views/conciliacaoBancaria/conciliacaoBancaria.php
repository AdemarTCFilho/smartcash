<link rel="stylesheet" href="<?= base_url('application/views/contasPagar/contasPagar.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/conciliacaoBancaria.css') ?>">

<div class="container">
    <div class="top">
        <div>
            <div class="small">FINANCEIRO</div>
            <div class="title">Conciliação Bancária</div>
            <div class="sub">Comparação de lançamentos com extrato bancário</div>
        </div>
        <div class="filters">
            
        </div>
    </div>

    <div class="linha-btn">
        <button type="button" id="btnNovoLancamento">
            <i class="fa fa-plus" aria-hidden="true" style="font-size:12px"></i>&nbsp; Lançamento manual
        </button>
        <button type="button" id="btnExportarCSV" style="background:#11183D;border:1px solid var(--border);color:#8E98C6;">
            <i class="fa fa-download" aria-hidden="true"></i>&nbsp; Exportar CSV
        </button>
    </div>

    <div class="cards" id="cardsResumo">
        <div class="card">
            <div class="label">ENTRADAS</div>
            <div class="value" id="totalEntradas">R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">SAÍDAS</div>
            <div class="value red" id="totalSaidas">R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">SALDO</div>
            <div class="value" id="totalSaldo">R$ 0,00</div>
        </div>
    </div>

    <div class="filters-panel">
        <small>conta:</small>
        <select id="filtroConta" style="width:300px;">
            <option value="">Selecione a conta</option>
        </select>
        <small>De:</small>
        <input type="date" id="filtroDataInicio" style="height:35px;">
        <small>Até:</small>
        <input type="date" id="filtroDataFim" style="height:35px;">
        <label>
            <input type="checkbox" id="filtroNaoConciliadas" style="width:18px;height:18px;">
            <span style="margin-left:6px;">Somente não conciliadas</span>
        </label>
    </div>

    <div class="panel">
        <div class="div-espaco">
            <div class="panel-title">Movimentações</div>
            <div class="panel-sub" id="totalRegistros"></div>
        </div>
        <div class="tablewrap">
            <table>
                <thead>
                    <tr>
                        <th>DATA</th>
                        <th>CONTA</th>
                        <th>DESCRIÇÃO</th>
                        <th>TIPO</th>
                        <th>VALOR</th>
                        <th>CONCILIADA</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody id="tabelaMovimentacoes">
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--muted);padding:30px;">Selecione uma conta bancária.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('assets/js/conciliacaoBancaria.js') ?>"></script>
