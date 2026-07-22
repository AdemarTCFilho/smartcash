<link rel="stylesheet" href="<?= base_url('application/views/contasReceber/contasReceber.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="top">
        <div>
            <div class="small">FINANCEIRO</div>
            <div class="title">Contas a Receber</div>
            <div class="sub">Lançamentos de receitas</div>
        </div>
        <div class="filters">
            <select class="btn" id="filtroMes" style="height: 43px;"></select>
            <select class="btn" id="filtroEmpresa" style="height: 43px;">
                <option value="">Todas as empresas</option>
            </select>
        </div>
    </div>

    <div class="linha-btn">
        <button type="button" id="btnImportarCSV" class="btn-importar">
            <i class="fa fa-upload" aria-hidden="true" style="font-size:12px"></i>&nbsp; Importar CSV
        </button>
        <button type="button" id="btnNovaConta">
            <i class="fa fa-plus" aria-hidden="true" style="font-size:12px"></i>&nbsp; Nova conta
        </button>
    </div>

    <div class="cards" id="cardsResumo">
        <div class="card">
            <div class="label">TOTAL A RECEBER</div>
            <div class="value" id="totalAReceber">R$ 0,00</div>
            <div class="muted" id="totalAReceberContas">0 conta(s)</div>
        </div>
        <div class="card">
            <div class="label">RECEBIDAS (HISTÓRICO)</div>
            <div class="value" id="totalRecebido">R$ 0,00</div>
            <div class="muted" id="totalRecebidoContas">0 conta(s)</div>
        </div>
        <div class="card">
            <div class="label">PRÓXIMO VENCIMENTO</div>
            <div class="value" id="proximoVencimento">-</div>
            <div class="muted" id="proximoVencimentoCliente"></div>
        </div>
    </div>

    <div class="cards-duplo">
        <div class="card receitas">
            <div class="texto-card">Receitas por categoria</div>
            <div  id="receitasCategoriaContent"></div>
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
                <tbody id="tabelaContasReceber"></tbody>
            </table>
        </div>
    </div>
</div>

<input type="file" id="csvFileInput" accept=".csv" style="display:none">

<script>
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('application/views/contasReceber/contasReceber.js') ?>"></script>
