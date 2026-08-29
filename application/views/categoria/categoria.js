function openTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.btn').forEach(el => el.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');

    if ($.fn.DataTable) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    }
}

function statusBadge(status) {
    if (status == 1) {
        return '<span class="status-ativo">Ativo</span>';
    }
    return '<span class="status-inativo">Inativo</span>';
}

function tipoBadge(tipo) {
    if (tipo == 'SAIDA') {
        return '<span class="status-inativo">Saída</span>';
    }
    return '<span class="status-ativo">Entrada</span>';
}

function carregarCategorias() {
    let tabelaEl = document.querySelector('#tabelaCategorias').closest('table');

    if ($.fn.DataTable.isDataTable(tabelaEl)) {
        $(tabelaEl).DataTable().destroy();
    }

    fetch(siteUrl + 'categoria/listar')
        .then(res => res.json())
        .then(res => {
            let html = '';
            res.data.forEach(c => {
                let statusHtml = statusBadge(c.status);
                html += '<tr>' +
                    '<td>' + c.idCategoria + '</td>' +
                    '<td>' + c.nomeCategoria + '</td>' +
                    '<td>' + tipoBadge(c.tipo) + '</td>' +
                    '<td>' + (c.descricaoCategoria || '') + '</td>' +
                    '<td>' + statusHtml + '</td>' +
                    '<td>' +
                        "<span class='edit' onclick='editarCategoria(" + c.idCategoria + ")' title='Editar Categoria'>✎</span>" +
                        "<span class='edit' onclick='excluirCategoria(" + c.idCategoria + ")' title='Excluir Categoria'><i class='fa fa-trash-o' aria-hidden='true'></i></span>" +
                    '</td>' +
                '</tr>';
            });
            document.getElementById('tabelaCategorias').innerHTML = html;

            $(tabelaEl).DataTable({
                "ordering": false,
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "language": {
                    "url": baseUrl + "assets/js/dataTable_pt-br.json"
                }
            });

            let opts = '<option value="">Selecione uma categoria</option>';
            res.data.forEach(c => {
                opts += '<option value="' + c.idCategoria + '">' + c.nomeCategoria + '</option>';
            });
            let selectSub = document.getElementById('selectCategoriaSub');
            selectSub.innerHTML = opts;

            if ($(selectSub).data('select2')) $(selectSub).select2('destroy');
            $(selectSub).select2({
                width: '100%',
                placeholder: 'Selecione uma categoria',
                allowClear: true,
            });
        });
}

function carregarSubCategorias() {
    let tabelaEl = document.querySelector('#tabelaSubCategorias').closest('table');

    if ($.fn.DataTable.isDataTable(tabelaEl)) {
        $(tabelaEl).DataTable().destroy();
    }

    fetch(siteUrl + 'categoria/listarSubCategorias')
        .then(res => res.json())
        .then(res => {
            let html = '';
            res.data.forEach(s => {
                let statusHtml = statusBadge(s.status);
                html += '<tr>' +
                    '<td>' + s.nomeSubCategoria + '</td>' +
                    '<td>' + s.nomeCategoria + '</td>' +
                    '<td>' + tipoBadge(s.tipo) + '</td>' +
                    '<td>' + statusHtml + '</td>' +
                    '<td>' +
                        "<span class='edit' onclick='editarSubCategoria(" + s.idSubCategoria + ")' title='Editar SubCategoria'>✎</span>" +
                        "<span class='edit' onclick='excluirSubCategoria(" + s.idSubCategoria + ")' title='Excluir SubCategoria'><i class='fa fa-trash-o' aria-hidden='true'></i></span>" +
                    '</td>' +
                '</tr>';
            });
            document.getElementById('tabelaSubCategorias').innerHTML = html;

            $(tabelaEl).DataTable({
                "ordering": false,
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "language": {
                    "url": baseUrl + "assets/js/dataTable_pt-br.json"
                }
            });
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

function salvarSubCategoria() {
    let form = document.getElementById('formSubCategoria');
    let data = new URLSearchParams(new FormData(form));
    fetch(siteUrl + 'categoria/salvarSubCategoria', { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Sucesso', res.message, 'success');
                form.reset();
                $(form.querySelector('[name="idCategoria"]')).val('').trigger('change');
                carregarSubCategorias();
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
                    carregarSubCategorias();
                });
        }
    });
}

function excluirSubCategoria(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir esta subcategoria?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            let data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'categoria/excluirSubCategoria', { method: 'POST', body: data })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('Excluído', res.message, 'success');
                    carregarSubCategorias();
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
            form.querySelector('[name="tipo"]').value = data.tipo;
            form.querySelector('[name="descricaoCategoria"]').value = data.descricaoCategoria || '';
            form.querySelector('[name="status"]').value = data.status;
            document.querySelector('#formCategoria .baixar-btn').textContent = 'Salvar';
        });
}

function editarSubCategoria(id) {
    fetch(siteUrl + 'categoria/getDadosSubCategoria?id=' + id)
        .then(res => res.json())
        .then(data => {
            let form = document.getElementById('formSubCategoria');
            form.querySelector('[name="id"]').value = data.idSubCategoria;
            $(form.querySelector('[name="idCategoria"]')).val(String(data.idCategoria)).trigger('change');
            form.querySelector('[name="nomeSubCategoria"]').value = data.nomeSubCategoria;
            form.querySelector('[name="status"]').value = data.status;
            openTab('tab-subcategoria');
        });
}

document.addEventListener('DOMContentLoaded', function() {
    carregarCategorias();
    carregarSubCategorias();
});
