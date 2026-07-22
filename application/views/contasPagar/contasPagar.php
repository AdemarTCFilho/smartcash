<link rel="stylesheet" href="<?= base_url('application/views/contasPagar/contasPagar.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="top">
        <div>
            <div class="small">FINANCEIRO</div>
            <div class="title">Contas a Pagar</div>
            <div class="sub">Lançamentos de despesas</div>
        </div>
        <div class="filters">
            <select class="btn" id="filtroMes" style="height: 43px;"></select>
            <select class="btn" id="filtroEmpresa" style="height: 43px;">
                <option value="">Todas as empresas</option>
            </select>
        </div>
    </div>

    <div class="linha-btn">
        <button type="button" id="btnNovaConta">
            <i class="fa fa-plus" aria-hidden="true" style="font-size:12px"></i>&nbsp; Nova conta
        </button>
    </div>

    <div class="cards" id="cardsResumo">
        <div class="card">
            <div class="label">TOTAL A PAGAR</div>
            <div class="value" id="totalAPagar">R$ 0,00</div>
            <div class="muted" id="totalAPagarContas">0 conta(s)</div>
        </div>
        <div class="card">
            <div class="label">PAGAS (HISTÓRICO)</div>
            <div class="value" id="totalPago">R$ 0,00</div>
            <div class="muted" id="totalPagoContas">0 conta(s)</div>
        </div>
        <div class="card">
            <div class="label">PRÓXIMO VENCIMENTO</div>
            <div class="value" id="proximoVencimento">-</div>
            <div class="muted" id="proximoVencimentoCliente"></div>
        </div>
    </div>

    <div class="cards-duplo">
        <div class="card despesas">
            <div class="texto-card">Despesas por categoria</div>
            <div id="despesasCategoriaContent"></div>
        </div>
        <div class="card vencimentos">
            <div class="texto-card">Próximos vencimentos</div>
            <div id="proximosVencimentosContent"></div>
        </div>
    </div>

    <div class="panel">
        <div class="div-espaco">
            <div class="panel-title">Lançamentos</div>
            <div class="panel-sub" id="totalRegistros"></div>
        </div>
        <div class="tablewrap">
            <table>
                <thead>
                    <tr>
                        <th>CLIENTE</th>
                        <th>USUÁRIO</th>
                        <th>EMPRESA</th>
                        <th>UNIDADE</th>
                        <th>SUBUNIDADE</th>
                        <th>VALOR</th>
                        <th>VENCIMENTO</th>
                        <th>CATEGORIA</th>
                        <th>OBS</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody id="tabelaContasPagar"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('application/views/contasPagar/contasPagar.js') ?>"></script>
