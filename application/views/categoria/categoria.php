<link rel="stylesheet" href="<?= base_url('application/views/categoria/categoria.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="title">Categorias</div>
    <div class="sub">Cadastro e gerenciamento de categorias</div>

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

<script>
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('application/views/categoria/categoria.js') ?>"></script>
