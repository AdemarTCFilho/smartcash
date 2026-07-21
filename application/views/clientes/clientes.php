<link rel="stylesheet" href="<?= base_url('application/views/clientes/clientes.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="title">Clientes</div>
    <div class="sub">Cadastro e gerenciamento de clientes</div>

    <div class="card panel table-panel bottom">
        <div class="texto-card">CADASTRO DE CLIENTE</div><hr/>
        <form id="formCliente">
            <input type="hidden" name="id" value="">
            <div class="texto-card">
                <div class="section active card texto-card">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nome do Cliente *</label>
                            <input class="swal-input" type="text" name="nomeCliente" placeholder="Digite o nome" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label>Sexo *</label>
                            <select class="swal-input" name="sexo" style="height: 38px;">
                                <option value="">Selecione</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tipo Pessoa</label>
                            <select class="swal-input" name="pessoa_fisica" style="height: 38px;" onchange="aplicarMascaraDocumento()">
                                <option value="1">Pessoa Física</option>
                                <option value="0">Pessoa Jurídica</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Documento *</label>
                            <input class="swal-input" type="text" name="documento" placeholder="Digite o CPF/CNPJ" maxlength="18" oninput="aplicarMascaraDocumento(this)">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="swal-input" name="status" style="height: 38px;">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Telefone</label>
                            <input class="swal-input" type="text" name="telefone" placeholder="Digite o telefone" maxlength="15" oninput="aplicarMascaraTelefone(this)">
                        </div>
                        <div class="form-group">
                            <label>Celular</label>
                            <input class="swal-input" type="text" name="celular" placeholder="Digite o celular" maxlength="15" oninput="aplicarMascaraTelefone(this)">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input class="swal-input" type="email" name="email" placeholder="Digite o email" maxlength="100">
                        </div>
                    </div>

                    <div class="section-title">Endereço</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Rua</label>
                            <input class="swal-input" type="text" name="rua" placeholder="Digite a rua" maxlength="70">
                        </div>
                        <div class="form-group">
                            <label>Número</label>
                            <input class="swal-input" type="text" name="numero" placeholder="Digite o número" maxlength="15">
                        </div>
                        <div class="form-group">
                            <label>Bairro</label>
                            <input class="swal-input" type="text" name="bairro" placeholder="Digite o bairro" maxlength="45">
                        </div>
                        <div class="form-group">
                            <label>Cidade</label>
                            <input class="swal-input" type="text" name="cidade" placeholder="Digite a cidade" maxlength="45">
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <select class="swal-input" name="estado" style="height: 38px;">
                                <option value="">Selecione</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>CEP</label>
                            <input class="swal-input" type="text" name="cep" placeholder="Digite o CEP" maxlength="9" oninput="aplicarMascaraCep(this)">
                        </div>
                        <div class="form-group">
                            <label>Complemento</label>
                            <input class="swal-input" type="text" name="complemento" placeholder="Digite o complemento" maxlength="45">
                        </div>
                    </div>

                    <div class="section-title">Informações Adicionais</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Contato</label>
                            <input class="swal-input" type="text" name="contato" placeholder="Digite o contato" maxlength="45">
                        </div>
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="fornecedor" value="1"> Fornecedor
                            </label>
                        </div>
                    </div>
                </div><br/>
                <button type="button" class="baixar-btn" onclick="salvarCliente()">Adicionar Cliente</button>
            </div>
        </form>
    </div>

    <div class="card panel">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOME</th>
                    <th>SEXO</th>
                    <th>DOCUMENTO</th>
                    <th>TELEFONE</th>
                    <th>EMAIL</th>
                    <th>CIDADE</th>
                    <th>ESTADO</th>
                    <th>STATUS</th>
                    <th>FORNECEDOR</th>
                    <th>AÇÃO</th>
                </tr>
            </thead>
            <tbody id="tabelaClientes"></tbody>
        </table>
    </div>
</div>

<script>
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="<?= base_url('application/views/clientes/clientes.js') ?>"></script>
