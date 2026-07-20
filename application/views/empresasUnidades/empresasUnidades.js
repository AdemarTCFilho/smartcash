function openTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.btn').forEach(el => el.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}

function applyCNPJMask(input) {
    let value = input.value.replace(/\D/g, '');
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
    
    input.value = value;
}

function formatCNPJ(cnpj) {
    if (!cnpj) return '';
    let v = cnpj.replace(/\D/g, '');
    if (v.length !== 14) return cnpj;
    return v.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
}

function statusBadge(status) {
    if (status == 1) {
        return '<span class="status-ativo">Ativo</span>';
    }
    return '<span class="status-inativo">Inativo</span>';
}

function carregarEmpresas() {
    fetch(siteUrl + 'empresasUnidades/listarEmpresas')
        .then(res => res.json())
        .then(res => {
            let html = '';
            res.data.forEach(e => {
                html += '<tr>' +
                    '<td>' + e.nomeEmpresa + '</td>' +
                    '<td>' + formatCNPJ(e.cnpjEmpresa) + '</td>' +
                    '<td>' + (e.enderecoEmpresa || '') + '</td>' +
                    '<td>' + statusBadge(e.status) + '</td>' +
                    '<td>' +
                        "<span class='edit' onclick='editarEmpresa(" + e.idEmpresa + ")' title='Editar Empresa'>✎</span>" +
                        "<span class='edit' onclick='excluirEmpresa(" + e.idEmpresa + ")' title='Excluir Empresa'><i class='fa fa-trash-o' aria-hidden='true'></i></span>" +
                    '</td>' +
                '</tr>';
            });
            document.getElementById('tabelaEmpresas').innerHTML = html;

            let opts = '<option value="">Selecione uma empresa</option>';
            res.data.forEach(e => {
                opts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
            });
            document.getElementById('selectEmpresaUnidade').innerHTML = opts;
            document.getElementById('selectEmpresaSub').innerHTML = opts;
        });
}

function carregarUnidades() {
    fetch(siteUrl + 'empresasUnidades/listarUnidades')
        .then(res => res.json())
        .then(res => {
            let html = '';
            res.data.forEach(u => {
                html += '<tr>' +
                    '<td>' + u.nomeUnidade + '</td>' +
                    '<td>' + u.nomeEmpresa + '</td>' +
                    '<td>' + formatCNPJ(u.cnpjEmpresa) + '</td>' +
                    '<td>' + (u.enderecoUnidade || '') + '</td>' +
                    '<td>' + statusBadge(u.status) + '</td>' +
                    '<td>' +
                        "<span class='edit' onclick='editarUnidade(" + u.idUnidade + ")' title='Editar Unidade'>✎</span>" +
                        "<span class='edit' onclick='excluirUnidade(" + u.idUnidade + ")' title='Excluir Unidade'><i class='fa fa-trash-o' aria-hidden='true'></i></span>" +
                    '</td>' +
                '</tr>';
            });
            document.getElementById('tabelaUnidades').innerHTML = html;
        });
}

function carregarSubUnidades() {
    fetch(siteUrl + 'empresasUnidades/listarSubUnidades')
        .then(res => res.json())
        .then(res => {
            let html = '';
            res.data.forEach(s => {
                html += '<tr>' +
                    '<td>' + s.nomeSubUnidade + '</td>' +
                    '<td>' + s.nomeUnidade + '</td>' +
                    '<td>' + s.nomeEmpresa + '</td>' +
                    '<td>' + formatCNPJ(s.cnpjEmpresa) + '</td>' +
                    '<td>' + statusBadge(s.status) + '</td>' +
                    '<td>' +
                        "<span class='edit' onclick='editarSubUnidade(" + s.idSubUnidade + ")' title='Editar SubUnidade'>✎</span>" +
                        "<span class='edit' onclick='excluirSubUnidade(" + s.idSubUnidade + ")' title='Excluir SubUnidade'><i class='fa fa-trash-o' aria-hidden='true'></i></span>" +
                    '</td>' +
                '</tr>';
            });
            document.getElementById('tabelaSubUnidades').innerHTML = html;
        });
}

function carregarUnidadesPorEmpresa(empresaId) {
    let select = document.getElementById('selectUnidadeSub');
    select.innerHTML = '<option value="">Carregando...</option>';
    if (!empresaId) {
        select.innerHTML = '<option value="">Selecione uma unidade</option>';
        return Promise.resolve();
    }
    return fetch(siteUrl + 'empresasUnidades/listarUnidadesPorEmpresa?idEmpresa=' + empresaId)
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Selecione uma unidade</option>';
            res.data.forEach(u => {
                opts += '<option value="' + u.idUnidade + '">' + u.nomeUnidade + '</option>';
            });
            select.innerHTML = opts;
        });
}

function salvarEmpresa() {
    let form = document.getElementById('formEmpresa');
    let data = new URLSearchParams(new FormData(form));
    fetch(siteUrl + 'empresasUnidades/salvarEmpresa', { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Sucesso', res.message, 'success');
                form.reset();
                carregarEmpresas();
            } else {
                Swal.fire('Erro', res.message, 'error');
            }
        });
}

function salvarUnidade() {
    let form = document.getElementById('formUnidade');
    let data = new URLSearchParams(new FormData(form));
    fetch(siteUrl + 'empresasUnidades/salvarUnidade', { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Sucesso', res.message, 'success');
                form.reset();
                carregarUnidades();
            } else {
                Swal.fire('Erro', res.message, 'error');
            }
        });
}

function salvarSubUnidade() {
    let form = document.getElementById('formSubUnidade');
    let data = new URLSearchParams(new FormData(form));
    fetch(siteUrl + 'empresasUnidades/salvarSubUnidade', { method: 'POST', body: data })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Sucesso', res.message, 'success');
                form.reset();
                carregarSubUnidades();
                document.getElementById('selectUnidadeSub').innerHTML = '<option value="">Selecione uma unidade</option>';
            } else {
                Swal.fire('Erro', res.message, 'error');
            }
        });
}

function excluirEmpresa(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir esta empresa?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            let data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'empresasUnidades/excluirEmpresa', { method: 'POST', body: data })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('Excluído', res.message, 'success');
                    carregarEmpresas();
                });
        }
    });
}

function excluirUnidade(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir esta unidade?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            let data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'empresasUnidades/excluirUnidade', { method: 'POST', body: data })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('Excluído', res.message, 'success');
                    carregarUnidades();
                });
        }
    });
}

function excluirSubUnidade(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir esta subunidade?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            let data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'empresasUnidades/excluirSubUnidade', { method: 'POST', body: data })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('Excluído', res.message, 'success');
                    carregarSubUnidades();
                });
        }
    });
}

function editarEmpresa(id) {
    fetch(siteUrl + 'empresasUnidades/getDadosEmpresa?id=' + id)
        .then(res => res.json())
        .then(data => {
            let form = document.getElementById('formEmpresa');
            form.querySelector('[name="id"]').value = data.idEmpresa;
            form.querySelector('[name="nomeEmpresa"]').value = data.nomeEmpresa;
            form.querySelector('[name="cnpjEmpresa"]').value = formatCNPJ(data.cnpjEmpresa);
            form.querySelector('[name="enderecoEmpresa"]').value = data.enderecoEmpresa || '';
            form.querySelector('[name="status"]').value = data.status;
            openTab('tab-empresa');
        });
}

function editarUnidade(id) {
    fetch(siteUrl + 'empresasUnidades/getDadosUnidade?id=' + id)
        .then(res => res.json())
        .then(data => {
            let form = document.getElementById('formUnidade');
            form.querySelector('[name="id"]').value = data.idUnidade;
            form.querySelector('[name="nomeUnidade"]').value = data.nomeUnidade;
            form.querySelector('[name="idEmpresa"]').value = data.idEmpresa;
            form.querySelector('[name="enderecoUnidade"]').value = data.enderecoUnidade || '';
            form.querySelector('[name="status"]').value = data.status;
            openTab('tab-unidade');
        });
}

function editarSubUnidade(id) {
    fetch(siteUrl + 'empresasUnidades/getDadosSubUnidade?id=' + id)
        .then(res => res.json())
        .then(data => {
            let form = document.getElementById('formSubUnidade');
            form.querySelector('[name="id"]').value = data.idSubUnidade;
            form.querySelector('[name="nomeSubUnidade"]').value = data.nomeSubUnidade;
            form.querySelector('[name="idEmpresa"]').value = data.idEmpresa;
            form.querySelector('[name="status"]').value = data.status;
            carregarUnidadesPorEmpresa(data.idEmpresa).then(() => {
                form.querySelector('[name="idUnidade"]').value = data.idUnidade;
            });
            openTab('tab-subunidade');
        });
}

document.addEventListener('DOMContentLoaded', function() {
    carregarEmpresas();
    carregarUnidades();
    carregarSubUnidades();
});
