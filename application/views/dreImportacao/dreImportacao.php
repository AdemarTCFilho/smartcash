<link rel="stylesheet" href="<?= base_url('application/views/dreImportacao/dreImportacao.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="dre-importacao-container">
    <div class="dre-importacao-header">
        <div class="dre-importacao-title">
            <h2>DRE Importação</h2>
            <span class="dre-importacao-subtitle">Importe planilhas financeiras e mapeie despesas e centros de custo</span>
        </div>
        <div class="dre-importacao-actions">
            <button class="dre-btn dre-btn-primary" id="btnImportar">
                <i class="fa fa-upload"></i> Importar Planilha
            </button>
            <button class="dre-btn dre-btn-danger" id="btnLimpar">
                <i class="fa fa-trash"></i> Limpar
            </button>
        </div>
    </div>

    <div class="dre-importacao-notice">
        <i class="fa fa-info-circle"></i>
        Formatos aceitos: CSV (separado por <strong>;</strong>), XLS e XLSX. Colunas esperadas: DATA, DATA_BALANCETE, HISTORICO, VALOR, STATUS, DETALHAMENTO_HIST, DESPESAS, CENTRO DE CUSTO. Status <strong>C</strong> = Crédito, <strong>D</strong> = Débito.
    </div>

    <div class="dre-importacao-tabs">
        <button class="dre-tab active" data-tab="credito">
            <i class="fa fa-arrow-up"></i> CRÉDITO <span class="dre-tab-count" id="countCredito">0</span>
        </button>
        <button class="dre-tab" data-tab="debito">
            <i class="fa fa-arrow-down"></i> DÉBITO <span class="dre-tab-count" id="countDebito">0</span>
        </button>
    </div>

    <div class="dre-importacao-table-wrapper">
        <div id="tabCredito" class="dre-tab-content active">
            <table class="dre-table" id="tabelaCredito">
                <thead>
                    <tr>
                        <th>DATA</th>
                        <th>HISTÓRICO</th>
                        <th>VALOR</th>
                        <th>DETALHAMENTO</th>
                        <th>DESPESAS</th>
                        <th>CENTRO DE CUSTO</th>
                    </tr>
                </thead>
                <tbody id="bodyCredito">
                    <tr><td colspan="6" class="dre-empty">Nenhum registro importado</td></tr>
                </tbody>
            </table>
        </div>

        <div id="tabDebito" class="dre-tab-content">
            <table class="dre-table" id="tabelaDebito">
                <thead>
                    <tr>
                        <th>DATA</th>
                        <th>HISTÓRICO</th>
                        <th>VALOR</th>
                        <th>DETALHAMENTO</th>
                        <th>DESPESAS</th>
                        <th>CENTRO DE CUSTO</th>
                    </tr>
                </thead>
                <tbody id="bodyDebito">
                    <tr><td colspan="6" class="dre-empty">Nenhum registro importado</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="dre-modal-overlay" id="modalImportar" style="display:none;">
    <div class="dre-modal">
        <div class="dre-modal-header">
            <h3>Importar Planilha</h3>
            <button class="dre-modal-close" id="btnFecharModal">&times;</button>
        </div>
        <div class="dre-modal-body">
            <div class="dre-upload-area" id="uploadArea">
                <i class="fa fa-cloud-upload fa-3x"></i>
                <p>Arraste o arquivo aqui ou clique para selecionar</p>
                <span>CSV, XLS ou XLSX (máx. 10MB)</span>
                <input type="file" id="fileInput" accept=".csv,.xls,.xlsx" style="display:none;">
            </div>
            <div class="dre-upload-file-info" id="fileInfo" style="display:none;">
                <i class="fa fa-file-excel-o"></i>
                <span id="fileName"></span>
                <button class="dre-btn-icon" id="btnRemoverArquivo"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="dre-modal-footer">
            <button class="dre-btn dre-btn-secondary" id="btnCancelarModal">Cancelar</button>
            <button class="dre-btn dre-btn-primary" id="btnConfirmarImport" disabled>Importar</button>
        </div>
    </div>
</div>

<script>
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('application/views/dreImportacao/dreImportacao.js') ?>"></script>
