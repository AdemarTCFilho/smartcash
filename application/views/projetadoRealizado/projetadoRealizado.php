<link rel="stylesheet" href="<?= base_url('application/views/projetadoRealizado/projetadoRealizado.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="top">
        <div>
            <div class="small">ACOMPANHAMENTO GERENCIAL</div>
            <div class="title">Projetado x Realizado</div>
            <div class="sub">Projetado e meta lançados manualmente · Realizado vindo de Contas a Pagar/Receber</div>
        </div>
        <div class="filters">
            <select class="btn" id="filtroMes" style="height: 43px;"></select>
            <select class="btn" id="filtroEmpresa" style="height: 43px;">
                <option value="">Todas as empresas</option>
            </select>
        </div>
    </div>

    <div class="linha-btn">
        <button type="button" id="btnNovaProjecao">
            <i class="fa fa-plus" aria-hidden="true" style="font-size:12px"></i>&nbsp; Nova projeção
        </button>
    </div>

    <div class="cards" id="cardsResumo">
        <div class="card">
            <div class="label">RECEITA REALIZADA</div>
            <div class="value">R$ 0,00</div>
            <div class="muted">Projetado R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">ATINGIMENTO DA META</div>
            <div class="value red">0%</div>
            <div class="muted">Abaixo da meta</div>
        </div>
        <div class="card">
            <div class="label">DESPESA REALIZADA</div>
            <div class="value">R$ 0,00</div>
            <div class="muted">Teto R$ 0,00</div>
        </div>
        <div class="card">
            <div class="label">RESULTADO REALIZADO</div>
            <div class="value">R$ 0,00</div>
            <div class="muted">Projetado R$ 0,00</div>
        </div>
    </div>

    <div class="panel">
        <div class="div-espaco">
            <div class="panel-title">Consolidado por empresa</div>
            <div class="panel-sub">Receita realizada x meta · resultado x projetado no período</div>
        </div>
        <div class="grid4" id="gridEmpresas"></div>
    </div>

    <div class="panel">
        <div class="div-espaco">
            <div class="panel-title">Detalhamento por unidade</div>
            <div class="panel-sub">Projetado, realizado, meta, atingimento, resultado, margens e status</div>
        </div>
        <div class="tablewrap">
            <table>
                <thead>
                    <tr>
                        <th>UNIDADE</th>
                        <th>EMPRESA</th>
                        <th>REC. PROJ.</th>
                        <th>REC. REAL</th>
                        <th>META REC.</th>
                        <th>DESP. PROJ.</th>
                        <th>DESP. REAL</th>
                        <th>TETO DESP.</th>
                        <th>OBS</th>
                        <th>MÊS REF.</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody id="tabelaProjReal"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('application/views/projetadoRealizado/projetadoRealizado.js') ?>"></script>
