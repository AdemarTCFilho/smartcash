function aplicarMascaraDocumento(input) {
    let tipo = document.querySelector('[name="pessoa_fisica"]').value;
    let el = input || document.querySelector('[name="documento"]');
    let value = el.value.replace(/\D/g, '');

    if (tipo == 1) {
        if (value.length > 11) value = value.slice(0, 11);
        if (value.length > 9) {
            value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1.$2.$3-$4');
        } else if (value.length > 6) {
            value = value.replace(/^(\d{3})(\d{3})(\d{0,3})$/, '$1.$2.$3');
        } else if (value.length > 3) {
            value = value.replace(/^(\d{3})(\d{0,3})$/, '$1.$2');
        }
    } else {
        if (value.length > 14) value = value.slice(0, 14);
        if (value.length > 12) {
            value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
        } else if (value.length > 8) {
            value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{0,4})$/, '$1.$2.$3/$4');
        } else if (value.length > 5) {
            value = value.replace(/^(\d{2})(\d{3})(\d{0,3})$/, '$1.$2.$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,3})$/, '$1.$2');
        }
    }

    el.value = value;
}

function aplicarMascaraTelefone(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);

    if (value.length > 7) {
        value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    } else if (value.length > 2) {
        value = value.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
    }

    input.value = value;
}

function aplicarMascaraCep(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 8) value = value.slice(0, 8);
    if (value.length > 5) {
        value = value.replace(/^(\d{5})(\d{3})$/, '$1-$2');
    }
    input.value = value;
}

function formatDocumento(cliente) {
    if (!cliente.documento) return '';
    let v = cliente.documento.replace(/\D/g, '');
    if (v.length === 11) {
        return v.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1.$2.$3-$4');
    }
    if (v.length === 14) {
        return v.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
    }
    return cliente.documento;
}

function formatTelefone(value) {
    if (!value) return '';
    let v = value.replace(/\D/g, '');
    if (v.length === 11) {
        return v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    }
    if (v.length === 10) {
        return v.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
    }
    return value;
}

function formatCep(value) {
    if (!value) return '';
    let v = value.replace(/\D/g, '');
    if (v.length === 8) {
        return v.replace(/^(\d{5})(\d{3})$/, '$1-$2');
    }
    return value;
}

function statusBadge(value) {
    return value == 1
        ? '<span class="status-ativo">Ativo</span>'
        : '<span class="status-inativo">Inativo</span>';
}

function fornecedorBadge(value) {
    return value == 1
        ? '<span class="status-ativo">Sim</span>'
        : '<span class="status-inativo">Não</span>';
}

function carregarClientes() {
    fetch(siteUrl + 'clientes/listar')
        .then(res => res.json())
        .then(res => {
            let html = '';
            res.data.forEach(c => {
                html += '<tr>' +
                    '<td>' + c.idClientes + '</td>' +
                    '<td>' + c.nomeCliente + '</td>' +
                    '<td>' + (c.sexo || '') + '</td>' +
                    '<td>' + formatDocumento(c) + '</td>' +
                    '<td>' + formatTelefone(c.telefone) + '</td>' +
                    '<td>' + c.email + '</td>' +
                    '<td>' + (c.cidade || '') + '</td>' +
                    '<td>' + (c.estado || '') + '</td>' +
                    '<td>' + statusBadge(c.status) + '</td>' +
                    '<td>' + fornecedorBadge(c.fornecedor) + '</td>' +
                    '<td>' +
                        "<span class='edit' onclick='editarCliente(" + c.idClientes + ")' title='Editar Cliente'>✎</span>" +
                        "<span class='edit' onclick='excluirCliente(" + c.idClientes + ")' title='Excluir Cliente'><i class='fa fa-trash-o' aria-hidden='true'></i></span>" +
                    '</td>' +
                '</tr>';
            });
            document.getElementById('tabelaClientes').innerHTML = html;
        });
}

function salvarCliente() {
    let form = document.getElementById('formCliente');
    let data = new URLSearchParams(new FormData(form));
    fetch(siteUrl + 'clientes/salvar', { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Sucesso', res.message, 'success');
                form.reset();
                document.querySelector('#formCliente .baixar-btn').textContent = 'Adicionar Cliente';
                carregarClientes();
            } else {
                Swal.fire('Erro', res.message, 'error');
            }
        });
}

function excluirCliente(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir este cliente?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            let data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'clientes/excluir', { method: 'POST', body: data })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Excluído', res.message, 'success');
                        carregarClientes();
                    } else {
                        Swal.fire('Erro', res.message, 'error');
                    }
                });
        }
    });
}

function editarCliente(id) {
    fetch(siteUrl + 'clientes/getDados?id=' + id)
        .then(res => res.json())
        .then(data => {
            let form = document.getElementById('formCliente');
            form.querySelector('[name="id"]').value = data.idClientes;
            form.querySelector('[name="nomeCliente"]').value = data.nomeCliente;
            form.querySelector('[name="sexo"]').value = data.sexo || '';
            form.querySelector('[name="status"]').value = data.status;
            form.querySelector('[name="pessoa_fisica"]').value = data.pessoa_fisica;
            form.querySelector('[name="documento"]').value = data.documento || '';
            form.querySelector('[name="telefone"]').value = formatTelefone(data.telefone);
            form.querySelector('[name="celular"]').value = formatTelefone(data.celular);
            form.querySelector('[name="email"]').value = data.email;
            form.querySelector('[name="rua"]').value = data.rua || '';
            form.querySelector('[name="numero"]').value = data.numero || '';
            form.querySelector('[name="bairro"]').value = data.bairro || '';
            form.querySelector('[name="cidade"]').value = data.cidade || '';
            form.querySelector('[name="estado"]').value = data.estado || '';
            form.querySelector('[name="cep"]').value = formatCep(data.cep);
            form.querySelector('[name="complemento"]').value = data.complemento || '';
            form.querySelector('[name="contato"]').value = data.contato || '';
            form.querySelector('[name="fornecedor"]').checked = data.fornecedor == 1;
            document.querySelector('#formCliente .baixar-btn').textContent = 'Salvar';
        });
}

document.addEventListener('DOMContentLoaded', function() {
    carregarClientes();
});
