<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/js/jquery.boot.css">
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>application/views/baixasFinanceiras/baixasFinanceiras.css">

<div class="container">
    <div class="topbar">
        <div>
            <div class="title">Baixas Financeiras</div>
            <div class="sub">Baixa de t&iacute;tulos a pagar e a receber</div>
        </div>
        <div class="filters">
            <select id="tipoPeriodoSelect" class="form-control" style="width:120px;">
                <option class="btn active" value="mensal">Mensal</option>
                <option class="btn" value="trimestral">Trimestral</option>
                <option class="btn" value="anual">Anual</option>
            </select>
            <select id="periodoSelect" class="form-control" style="width:140px;"></select>
            <select id="empresaSelect" class="form-control" style="width:200px;">
                <option value="">Todas as Empresas</option>
            </select>
        </div>
    </div>

    <div class="cards-duplo">
        <div class="card">
            <div class="label">CONTAS A PAGAR</div>
            <div class="value neg" id="saldoDevedor">R$ 0,00</div>
            <div class="muted"><span id="totalAPagar">0</span> t&iacute;tulos pendentes</div>
        </div>
        <div class="card">
            <div class="label">CONTAS A RECEBER</div>
            <div class="value pos" id="saldoReceber">R$ 0,00</div>
            <div class="muted"><span id="totalAReceber">0</span> t&iacute;tulos pendentes</div>
        </div>
    </div>

    <div class="panel">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; padding:12px;">
            <div class="linha-btn">
                <button class="btn active" data-tab="pagar">Contas a Pagar</button>
                <button class="btn" data-tab="receber">Contas a Receber</button>
                <button class="btn" data-tab="hist-pag">Hist&oacute;rico Pagamentos</button>
                <button class="btn" data-tab="hist-rec">Hist&oacute;rico Recebimentos</button>
            </div>
            <button id="exportCSV" class="btn" style="background:#1f8b4c;color:#fff;margin-left: 10%;">Exportar CSV</button>
            <input type="text" id="buscaInput" class="busca-input" placeholder="Buscar cliente..." />
        </div>

        <div id="tab-pagar" class="tab-content active">
            <div class="tablewrap">
                <table id="tabelaPagar">
                    <thead>
                        <tr>
                            <th>Fornecedor</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Valor Pago</th>
                            <th>Saldo</th>
                            <th>Status</th>
                            <th>A&ccedil;&atilde;o</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div id="tab-receber" class="tab-content">
            <div class="tablewrap">
                <table id="tabelaReceber">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Valor Recebido</th>
                            <th>Saldo</th>
                            <th>Status</th>
                            <th>A&ccedil;&atilde;o</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div id="tab-hist-pag" class="tab-content">
            <div class="tablewrap">
                <table id="tabelaHistPag">
                    <thead>
                        <tr>
                            <th>Fornecedor</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Valor Pago</th>
                            <th>Data Pagamento</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div id="tab-hist-rec" class="tab-content">
            <div class="tablewrap">
                <table id="tabelaHistRec">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Valor Recebido</th>
                            <th>Data Recebimento</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="baixarModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background:#0b1030;border:1px solid var(--border);">
            <div class="modal-header" style="border-bottom:1px solid var(--border);padding:15px 20px;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:.8;">&times;</button>
                <h4 class="modal-title" id="baixarModalTitle" style="color:#fff;">Baixar T&iacute;tulo</h4>
            </div>
            <div class="modal-body" style="padding:20px;">
                <input type="hidden" id="baixarId" />
                <input type="hidden" id="baixarTipo" />
                <div class="row">
                    <div class="col-md-12">
                        <b style="color:#fff;">Valor Original: R$ <span id="baixarValorOriginal">0,00</span></b>
                    </div>
                </div>
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-12">
                        <label style="color:#9aa3d0;">Valor a ser baixado</label>
                        <input type="text" id="baixarValor" class="form-control" style="background:#11183D;border:1px solid var(--border);color:#fff;border-radius:8px;" />
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--border);padding:15px 20px;">
                <button type="button" class="btn" data-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmarBaixa" class="btn" style="background:#5358ee;color:#fff;">Confirmar Baixa</button>
            </div>
        </div>
    </div>
</div>

<script>var base_url = '<?= base_url(); ?>';</script>
<script src="<?= base_url(); ?>application/views/baixasFinanceiras/baixasFinanceiras.js"></script>
