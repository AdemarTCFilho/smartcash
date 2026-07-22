<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/contasBancarias.css">

<div class="container">
    <div class="topbar">
        <div>
            <div class="title">Contas Banc&aacute;rias</div>
            <div class="sub">Cadastro de contas e saldos consolidados</div>
        </div>
        <button class="btn primary" id="btnNovaConta">
            <i class="fa fa-plus"></i> Nova conta
        </button>
    </div>

    <div class="saldo-consolidado">
        <div class="label">Saldo Consolidado</div>
        <div class="value" id="saldoConsolidado">R$ 0,00</div>
    </div>

    <div class="cards-grid" id="cardsGrid">
        <div class="vazio">Nenhuma conta banc&aacute;ria cadastrada.</div>
    </div>
</div>

<script>var siteUrl = '<?= site_url(); ?>/';</script>
<script src="<?= base_url(); ?>assets/js/contasBancarias.js"></script>
