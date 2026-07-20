<link rel="stylesheet" href="<?= base_url('application/views/empresasUnidades/empresasUnidades.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="title">Empresas / Unidades / SubUnidades</div>
    <div class="sub">Cadastrar Empresas suas Unidades e as SubUnidades</div>

    <div class="panel">
        <div class="linha-btn">
            <button class="btn active" onclick="openTab('tab-empresa')">Empresa</button>
            <button class="btn" onclick="openTab('tab-unidade')">Unidades</button>
            <button class="btn" onclick="openTab('tab-subunidade')">SubUnidades</button>
        </div>
    </div>

    <div id="tab-empresa" class="tab-content active">
        <div class="card panel table-panel bottom">
            <div class="texto-card">CADASTRO DE EMPRESA</div><hr/>
            <form id="formEmpresa">
                <input type="hidden" name="id" value="">
                <div class="texto-card">
                    <div class="section active card texto-card">
                        <label>Nome da Empresa</label>
                        <input class="swal-input" type="text" name="nomeEmpresa" placeholder="Digite o nome" style="width: 50%;">
                        <label>CNPJ</label>
                        <input class="swal-input" type="text" name="cnpjEmpresa" placeholder="Digite o CNPJ" style="width: 50%;" maxlength="18" oninput="applyCNPJMask(this)">
                        <label>Endereço</label>
                        <input class="swal-input" type="text" name="enderecoEmpresa" placeholder="Digite o endereço" style="width: 50%;">
                        <label>Status</label>
                        <select class="btn-select" name="status" style="height: 38px; text-align:left; width:50%;">
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div><br/>
                    <button type="button" class="baixar-btn" onclick="salvarEmpresa()">Salvar Empresa</button>
                </div>
            </form>
        </div>

        <div class="card panel">
            <table>
                <thead>
                    <tr>
                        <th>EMPRESA</th>
                        <th>CNPJ</th>
                        <th>ENDEREÇO</th>
                        <th>STATUS</th>
                        <th>AÇÃO</th>
                    </tr>
                </thead>
                <tbody id="tabelaEmpresas"></tbody>
            </table>
        </div>
    </div>

    <div id="tab-unidade" class="tab-content">
        <div class="card panel table-panel bottom">
            <div class="texto-card">CADASTRO DE UNIDADE</div><hr/>
            <form id="formUnidade">
                <input type="hidden" name="id" value="">
                <div class="texto-card">
                    <div class="section active card texto-card">
                        <label>Nome da Unidade</label>
                        <input class="swal-input" type="text" name="nomeUnidade" placeholder="Digite o nome" style="width: 50%;">
                        <label>Empresa</label>
                        <select class="btn-select" id="selectEmpresaUnidade" name="idEmpresa" style="height: 38px; text-align:left;">
                            <option value="">Selecione uma empresa</option>
                        </select>
                        <label>Endereço</label>
                        <input class="swal-input" type="text" name="enderecoUnidade" placeholder="Digite o endereço" style="width: 50%;">
                        <label>Status</label>
                        <select class="btn-select" name="status" style="height: 38px; text-align:left; width:50%;">
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div><br/>
                    <button type="button" class="baixar-btn" onclick="salvarUnidade()">Salvar Unidade</button>
                </div>
            </form>
        </div>

        <div class="card panel">
            <table>
                <thead>
                    <tr>
                        <th>UNIDADE</th>
                        <th>EMPRESA</th>
                        <th>CNPJ</th>
                        <th>ENDEREÇO</th>
                        <th>STATUS</th>
                        <th>AÇÃO</th>
                    </tr>
                </thead>
                <tbody id="tabelaUnidades"></tbody>
            </table>
        </div>
    </div>

    <div id="tab-subunidade" class="tab-content">
        <div class="card panel table-panel bottom">
            <div class="texto-card">CADASTRO DE SUBUNIDADE</div><hr/>
            <form id="formSubUnidade">
                <input type="hidden" name="id" value="">
                <div class="texto-card">
                    <div class="section active card texto-card">
                        <label>Nome da SubUnidade</label>
                        <input class="swal-input" type="text" name="nomeSubUnidade" placeholder="Digite o nome" style="width: 50%;">
                        <label>Empresa</label>
                        <select class="btn-select" id="selectEmpresaSub" name="idEmpresa" style="height: 38px; text-align:left;" onchange="carregarUnidadesPorEmpresa(this.value)">
                            <option value="">Selecione uma empresa</option>
                        </select>
                        <label>Unidade</label>
                        <select class="btn-select" id="selectUnidadeSub" name="idUnidade" style="height: 38px; text-align:left;">
                            <option value="">Selecione uma unidade</option>
                        </select>
                        <label>Status</label>
                        <select class="btn-select" name="status" style="height: 38px; text-align:left; width:50%;">
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div><br/>
                    <button type="button" class="baixar-btn" onclick="salvarSubUnidade()">Salvar SubUnidade</button>
                </div>
            </form>
        </div>

        <div class="card panel">
            <table>
                <thead>
                    <tr>
                        <th>SUBUNIDADE</th>
                        <th>UNIDADE</th>
                        <th>EMPRESA</th>
                        <th>CNPJ</th>
                        <th>STATUS</th>
                        <th>AÇÃO</th>
                    </tr>
                </thead>
                <tbody id="tabelaSubUnidades"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('application/views/empresasUnidades/empresasUnidades.js') ?>"></script>
