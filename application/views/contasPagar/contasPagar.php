<link rel="stylesheet" href="<?= base_url('application/views/contasPagar/contasPagar.css') ?>?v=<?= filemtime(APPPATH . 'views/contasPagar/contasPagar.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="top">
        <div>
            <div class="small">FINANCEIRO</div>
            <div class="title">Contas a Pagar</div>
            <div class="sub">Lançamentos de despesas</div>
        </div>
    </div>

    <div class="card panel filtro-bar">
        <div class="filtro-linha">
            <div class="filtro-busca">
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="text" id="filtroBusca" placeholder="Buscar por cliente..." onkeydown="if(event.key==='Enter') pesquisarContasPagar();">
            </div>
            <div class="filtro-periodo">
                <i class="fa fa-calendar" aria-hidden="true"></i>
                <input type="date" id="filtroDataDe">
                <span>a</span>
                <input type="date" id="filtroDataAte">
            </div>
            <button type="button" class="btn-atalho" onclick="aplicarAtalhoPeriodo('mes')">Mês</button>
            <button type="button" class="btn-atalho" onclick="aplicarAtalhoPeriodo('hoje')">Hoje</button>
            <button type="button" class="btn-filtro-avancado" id="btnFiltroAvancado" onclick="toggleFiltroAvancado()">
                <i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtro avançado
            </button>
            <button type="button" class="btn-pesquisar" onclick="pesquisarContasPagar()">Pesquisar</button>
            <div class="dropdown-exportar">
                <button type="button" class="btn-exportar" onclick="toggleExportarMenu(event)">
                    Exportar <i class="fa fa-caret-down" aria-hidden="true"></i>
                </button>
                <div class="exportar-menu" id="exportarMenu" style="display:none;">
                    <a href="#" onclick="exportarDados('pdf'); return false;"><i class="fa fa-file-pdf-o" aria-hidden="true" style="color:#ff5b67;"></i> Exportar em PDF</a>
                    <a href="#" onclick="exportarDados('excel'); return false;"><i class="fa fa-file-excel-o" aria-hidden="true" style="color:#00ff9d;"></i> Exportar em EXCEL</a>
                </div>
            </div>
        </div>
        <div class="filtro-avancado" id="filtroAvancado" style="display:none;">
            <select class="filtro-select select2-filtro" id="filtroEmpresa">
                <option value="">Todas as empresas</option>
            </select>
            <select class="filtro-select select2-filtro" id="filtroCategoria">
                <option value="">Todas as categorias</option>
            </select>
            <select class="filtro-select select2-filtro" id="filtroUnidade">
                <option value="">Todas as unidades</option>
            </select>
            <select class="filtro-select select2-filtro" id="filtroSubUnidade">
                <option value="">Todos os departamentos</option>
            </select>
            <select class="filtro-select select2-filtro" id="filtroUsuario">
                <option value="">Todos os usuários</option>
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
                        <th>DEPARTAMENTO</th>
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
<script src="<?= base_url('application/views/contasPagar/contasPagar.js') ?>?v=<?= filemtime(APPPATH . 'views/contasPagar/contasPagar.js') ?>"></script>
