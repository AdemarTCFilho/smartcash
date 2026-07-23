<link rel="stylesheet" href="<?= base_url('application/views/metasFinanceiras/metasFinanceiras.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container">
    <div class="top">
        <div>
            <div class="small">PLANEJAMENTO</div>
            <div class="title">Metas Financeiras</div>
            <div class="sub">Metas de receita, despesa e lucro por unidade</div>
        </div>
        <div class="filters">
            <select id="periodo-select" class="btn" style="height:43px;min-width:140px"></select>
            <select id="filtroEmpresa" class="btn" style="height:43px;min-width:160px">
                <option value="">Todas as empresas</option>
            </select>
        </div>
    </div>

    <div class="linha-btn">
        <button id="btnNovaMeta"><i class="bi bi-plus-lg" style="font-size:12px"></i>&nbsp; Nova meta</button>
        <button id="btnExportarCSV" style="background:#11183D;border:1px solid var(--border)"><i class="bi bi-download" style="font-size:12px"></i>&nbsp; CSV</button>
    </div>

    <div class="cards">
        <div class="card">
            <div class="label">META DE RECEITA</div>
            <div class="value receita" id="cardReceita">R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">META DE DESPESA (TETO)</div>
            <div class="value despesa" id="cardDespesa">R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">META DE LUCRO</div>
            <div class="value lucro" id="cardLucro">R$ 0,00</div>
        </div>
    </div>

    <div class="panel">
        <div class="div-espaco">
            <div class="panel-title">Metas por unidade</div>
            <div class="panel-sub">Metas cadastradas por unidade e empresa</div>
        </div>
        <div class="tablewrap">
            <table>
                <thead>
                    <tr>
                        <th>UNIDADE</th>
                        <th>EMPRESA</th>
                        <th>META RECEITA</th>
                        <th>META DESPESA</th>
                        <th>META LUCRO</th>
                        <th>REALIZADO RECEITA</th>
                        <th>ATINGIMENTO</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody id="tabelaMetas">
                    <tr><td colspan="8" style="color:#7E89B7;text-align:center;padding:30px">Carregando...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>var siteUrl = '<?= site_url() ?>';</script>
<script src="<?= base_url('application/views/metasFinanceiras/metasFinanceiras.js') ?>"></script>

