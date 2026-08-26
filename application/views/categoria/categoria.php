<link rel="stylesheet" href="<?= base_url('application/views/categoria/categoria.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="title">Categorias / Classificação no Plano de Contas</div>
    <div class="sub">Cadastro e gerenciamento de categorias e subcategorias</div>

    <div class="panel">
        <div class="linha-btn">
            <button class="btn active" onclick="openTab('tab-categoria')">Categorias</button>
            <button class="btn" onclick="openTab('tab-subcategoria')">Classificação no Plano de Contas</button>
        </div>
    </div>

    <div id="tab-categoria" class="tab-content active">
        <div class="card panel table-panel bottom">
            <div class="texto-card">CADASTRO DE CATEGORIA</div><hr/>
            <form id="formCategoria">
                <input type="hidden" name="id" value="">
                <div class="texto-card">
                    <div class="section active card texto-card">
                        <label>Nome da Categoria</label>
                        <input class="swal-input" type="text" name="nomeCategoria" placeholder="Digite o nome da categoria" style="width: 50%;" maxlength="100">
                        <label>Descrição da Categoria</label>
                        <textarea class="swal-input" name="descricaoCategoria" placeholder="Digite a descrição" style="width: 50%; height: 80px; resize: vertical;" maxlength="255"></textarea>
                        <label>Status</label>
                        <select class="swal-input" name="status" style="height: 38px; text-align:left; width: 50%;">
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div><br/>
                    <button type="button" class="baixar-btn" onclick="salvarCategoria()">Adicionar Categoria</button>
                </div>
            </form>
        </div>

        <div class="card panel">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>DESCRIÇÃO</th>
                        <th>STATUS</th>
                        <th>AÇÃO</th>
                    </tr>
                </thead>
                <tbody id="tabelaCategorias"></tbody>
            </table>
        </div>
    </div>

    <div id="tab-subcategoria" class="tab-content">
        <div class="card panel table-panel bottom">
            <div class="texto-card">CADASTRO DE CLASSIFICAÇÃO NO PLANO DE CONTAS</div><hr/>
            <form id="formSubCategoria">
                <input type="hidden" name="id" value="">
                <div class="texto-card">
                    <div class="section active card texto-card">
                        <label>Categoria</label>
                        <select class="swal-input" id="selectCategoriaSub" name="idCategoria" style="height: 38px; text-align:left; width: 50%;">
                            <option value="">Selecione uma categoria</option>
                        </select>
                        <label>Nome da Classificação</label>
                        <input class="swal-input" type="text" name="nomeSubCategoria" placeholder="Digite o nome" style="width: 50%;" maxlength="100">
                        <label>Status</label>
                        <select class="swal-input" name="status" style="height: 38px; text-align:left; width: 50%;">
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div><br/>
                    <button type="button" class="baixar-btn" onclick="salvarSubCategoria()">Salvar Classificação</button>
                </div>
            </form>
        </div>

        <div class="card panel">
            <table>
                <thead>
                    <tr>
                        <th>NOME</th>
                        <th>CATEGORIA</th>
                        <th>STATUS</th>
                        <th>AÇÃO</th>
                    </tr>
                </thead>
                <tbody id="tabelaSubCategorias"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var siteUrl = '<?= site_url() ?>';
    var baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('application/views/categoria/categoria.js') ?>"></script>
