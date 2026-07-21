function carregarCategorias() {
    fetch(siteUrl + 'categoria/listar')
        .then(res => res.json())
        .then(res => {
            let html = '';
            res.data.forEach(c => {
                let statusHtml = c.status == 1
                    ? '<span class="status-ativo">Ativo</span>'
                    : '<span class="status-inativo">Inativo</span>';
                html += '<tr>' +
                    '<td>' + c.idCategoria + '</td>' +
                    '<td>' + c.nomeCategoria + '</td>' +
                    '<td>' + (c.descricaoCategoria || '') + '</td>' +
                    '<td>' + statusHtml + '</td>' +
                    '<td>' +
                        "<span class='edit' onclick='editarCategoria(" + c.idCategoria + ")' title='Editar Categoria'>✎</span>" +
                        "<span class='edit' onclick='excluirCategoria(" + c.idCategoria + ")' title='Excluir Categoria'><i class='fa fa-trash-o' aria-hidden='true'></i></span>" +
                    '</td>' +
                '</tr>';
            });
            document.getElementById('tabelaCategorias').innerHTML = html;
        });
}

function salvarCategoria() {
    let form = document.getElementById('formCategoria');
    let data = new URLSearchParams(new FormData(form));
    fetch(siteUrl + 'categoria/salvar', { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Sucesso', res.message, 'success');
                form.reset();
                document.querySelector('#formCategoria .baixar-btn').textContent = 'Adicionar Categoria';
                carregarCategorias();
            } else {
                Swal.fire('Erro', res.message, 'error');
            }
        });
}

function excluirCategoria(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir esta categoria?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            let data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'categoria/excluir', { method: 'POST', body: data })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('Excluído', res.message, 'success');
                    carregarCategorias();
                });
        }
    });
}

function editarCategoria(id) {
    fetch(siteUrl + 'categoria/getDados?id=' + id)
        .then(res => res.json())
        .then(data => {
            let form = document.getElementById('formCategoria');
            form.querySelector('[name="id"]').value = data.idCategoria;
            form.querySelector('[name="nomeCategoria"]').value = data.nomeCategoria;
            form.querySelector('[name="descricaoCategoria"]').value = data.descricaoCategoria || '';
            form.querySelector('[name="status"]').value = data.status;
            document.querySelector('#formCategoria .baixar-btn').textContent = 'Salvar';
        });
}

document.addEventListener('DOMContentLoaded', function() {
    carregarCategorias();
});
