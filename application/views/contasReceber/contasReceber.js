document.addEventListener('DOMContentLoaded', function () {
    carregarEmpresas();
    carregarContasReceber();
    carregarDashboard();
    preencherMeses();

    document.getElementById('btnNovaConta').addEventListener('click', function () {
        abrirModal();
    });

    document.getElementById('btnImportarCSV').addEventListener('click', function () {
        document.getElementById('csvFileInput').click();
    });

    document.getElementById('csvFileInput').addEventListener('change', function (e) {
        importarCSV(e.target.files[0]);
    });

    document.getElementById('filtroMes').addEventListener('change', function () {
        carregarContasReceber();
        carregarDashboard();
    });

    document.getElementById('filtroEmpresa').addEventListener('change', function () {
        carregarContasReceber();
        carregarDashboard();
    });
});

function preencherMeses() {
    let select = document.getElementById('filtroMes');
    let meses = [];
    let now = new Date();
    for (let i = 0; i < 12; i++) {
        let d = new Date(now.getFullYear(), now.getMonth() - i, 1);
        let label = d.toLocaleDateString('pt-BR', { month: 'short', year: '2-digit' }).replace(' ', '/');
        let value = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
        meses.push({ value, label });
    }
    let html = '<option value="">Todos os meses</option>';
    meses.forEach(m => {
        html += '<option value="' + m.value + '">' + m.label + '</option>';
    });
    select.innerHTML = html;
}

function gerarMesesOptions() {
    let meses = [];
    let now = new Date();
    for (let i = 0; i < 12; i++) {
        let d = new Date(now.getFullYear(), now.getMonth() - i, 1);
        let label = d.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
        let value = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
        meses.push({ value, label });
    }
    return meses.map(m => '<option value="' + m.value + '">' + m.label + '</option>').join('');
}

function formatMoney(val) {
    if (!val && val !== 0) return 'R$ 0,00';
    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    let parts = dateStr.split('-');
    if (parts.length !== 3) return dateStr;
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

function mascaraMonetaria(el) {
    let v = el.value.replace(/\D/g, '');
    if (!v) { el.value = ''; return; }
    v = (parseInt(v) / 100).toFixed(2);
    el.value = v.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function carregarEmpresas() {
    fetch(siteUrl + 'contasReceber/listarEmpresas')
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Todas as empresas</option>';
            res.data.forEach(e => {
                opts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
            });
            document.getElementById('filtroEmpresa').innerHTML = opts;
        });
}

function carregarUnidades(empresaId, selectId) {
    let select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Carregando...</option>';
    if (!empresaId) {
        select.innerHTML = '<option value="">Selecione uma unidade</option>';
        return Promise.resolve();
    }
    return fetch(siteUrl + 'contasReceber/listarUnidadesPorEmpresa?idEmpresa=' + empresaId)
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Selecione uma unidade</option>';
            res.data.forEach(u => {
                opts += '<option value="' + u.idUnidade + '">' + u.nomeUnidade + '</option>';
            });
            select.innerHTML = opts;
        });
}

function carregarSubUnidades(unidadeId, selectId) {
    let select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Carregando...</option>';
    if (!unidadeId) {
        select.innerHTML = '<option value="">Selecione uma subunidade</option>';
        return Promise.resolve();
    }
    return fetch(siteUrl + 'contasReceber/listarSubUnidadesPorUnidade?idUnidade=' + unidadeId)
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Selecione uma subunidade</option>';
            res.data.forEach(s => {
                opts += '<option value="' + s.idSubUnidade + '">' + s.nomeSubUnidade + '</option>';
            });
            select.innerHTML = opts;
        });
}

function carregarDashboard() {
    fetch(siteUrl + 'contasReceber/getDadosDashboard')
        .then(res => res.json())
        .then(res => {
            document.getElementById('totalAReceber').textContent = formatMoney(res.totalAReceber.total);
            document.getElementById('totalAReceberContas').textContent = res.totalAReceber.totalContas + ' conta(s)';

            document.getElementById('totalRecebido').textContent = formatMoney(res.totalRecebido.total);
            document.getElementById('totalRecebidoContas').textContent = res.totalRecebido.totalContas + ' conta(s)';

            if (res.proximoVencimento) {
                document.getElementById('proximoVencimento').textContent = formatDate(res.proximoVencimento.vencimento);
                document.getElementById('proximoVencimentoCliente').textContent = res.proximoVencimento.nomeCliente || '';
            } else {
                document.getElementById('proximoVencimento').textContent = '-';
                document.getElementById('proximoVencimentoCliente').textContent = '';
            }

            let catHtml = '';
            if (res.receitasPorCategoria && res.receitasPorCategoria.length > 0) {
                res.receitasPorCategoria.forEach(c => {
                    catHtml += '<div class="item-categoria"><span>' + c.nomeCategoria + '</span><span>' + formatMoney(c.total) + '</span></div>';
                });
            } else {
                catHtml = '<div class="muted">Sem lançamentos ainda.</div>';
            }
            document.getElementById('receitasCategoriaContent').innerHTML = catHtml;

            let venHtml = '';
            if (res.proximosVencimentos && res.proximosVencimentos.length > 0) {
                res.proximosVencimentos.forEach(v => {
                    venHtml += '<div class="item-vencimento"><span class="vencimento-label">' + formatDate(v.vencimento) + '</span><span class="vencimento-valor">' + (v.nomeCliente || '') + ' — ' + formatMoney(v.valor) + '</span></div>';
                });
            } else {
                venHtml = '<div class="muted">Nenhuma conta em aberto.</div>';
            }
            document.getElementById('proximosVencimentosContent').innerHTML = venHtml;
        });
}

function carregarContasReceber() {
    let mes = document.getElementById('filtroMes').value;
    let empresa = document.getElementById('filtroEmpresa').value;

    fetch(siteUrl + 'contasReceber/listar')
        .then(res => res.json())
        .then(res => {
            let dados = res.data;

            if (mes) dados = dados.filter(d => d.vencimento && d.vencimento.substring(0, 7) === mes);
            if (empresa) dados = dados.filter(d => String(d.idEmpresa) === String(empresa));

            let html = '';
            dados.forEach(d => {
                html += '<tr>' +
                    '<td>' + (d.nomeCliente || '—') + '</td>' +
                    '<td>' + (d.nomeUsuario || '—') + '</td>' +
                    '<td>' + (d.nomeEmpresa || '—') + '</td>' +
                    '<td>' + (d.nomeUnidade || '—') + '</td>' +
                    '<td>' + (d.nomeSubUnidade || '—') + '</td>' +
                    '<td>' + formatMoney(d.valor) + '</td>' +
                    '<td>' + formatDate(d.vencimento) + '</td>' +
                    '<td>' + (d.nomeCategoria || '—') + '</td>' +
                    '<td>' + (d.observacoes ? d.observacoes.substring(0, 30) : '') + '</td>' +
                    '<td>' +
                        "<span class='edit' onclick='editarConta(" + d.idContaReceber + ")' title='Editar'>✎</span>" +
                        "<span class='edit' onclick='excluirConta(" + d.idContaReceber + ")' title='Excluir'><i class='fa fa-trash-o' aria-hidden='true'></i></span>" +
                    '</td>' +
                '</tr>';
            });
            document.getElementById('tabelaContasReceber').innerHTML = html;
            document.getElementById('totalRegistros').textContent = dados.length + ' registro(s)';
        });
}

function abrirModal(id) {
    let editando = !!id;

    Promise.all([
        fetch(siteUrl + 'contasReceber/listarClientes').then(r => r.json()),
        fetch(siteUrl + 'contasReceber/listarEmpresas').then(r => r.json()),
        fetch(siteUrl + 'contasReceber/listarCategorias').then(r => r.json()),
    ]).then(([clientesRes, empresasRes, categoriasRes]) => {
        let clientesOpts = '<option value="">Selecione um cliente</option>';
        clientesRes.data.forEach(c => {
            clientesOpts += '<option value="' + c.idClientes + '">' + c.nomeCliente + (c.documento ? ' — ' + c.documento : '') + '</option>';
        });

        let empresasOpts = '<option value="">Selecione uma empresa</option>';
        empresasRes.data.forEach(e => {
            empresasOpts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
        });

        let categoriasOpts = '<option value="">Selecione uma categoria</option>';
        categoriasRes.data.forEach(c => {
            categoriasOpts += '<option value="' + c.idCategoria + '">' + c.nomeCategoria + '</option>';
        });

        Swal.fire({
            title: editando ? 'Editar Conta a Receber' : 'Nova Conta a Receber',
            html: `
                <div style="text-align:left;">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-top:15px;">
                        <div>
                            <label class="swal-label">Cliente *</label>
                            <select id="swal-cliente" class="swal-select select2-modal">
                                ${clientesOpts}
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">Empresa *</label>
                            <select id="swal-empresa" class="swal-select select2-modal" onchange="carregarUnidadesModal(this.value)">
                                ${empresasOpts}
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">Unidade *</label>
                            <select id="swal-unidade" class="swal-select select2-modal" onchange="carregarSubUnidadesModal(this.value)">
                                <option value="">Selecione uma unidade</option>
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">SubUnidade</label>
                            <select id="swal-subunidade" class="swal-select select2-modal">
                                <option value="">Selecione uma subunidade</option>
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">Categoria</label>
                            <select id="swal-categoria" class="swal-select select2-modal">
                                ${categoriasOpts}
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">Valor (R$) *</label>
                            <input id="swal-valor" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)">
                        </div>
                        <div>
                            <label class="swal-label">Vencimento *</label>
                            <input id="swal-vencimento" type="date" class="swal-input">
                        </div>
                    </div>
                    <label class="swal-label" style="margin-top:15px;">Observações:</label>
                    <textarea id="swal-obs" class="swal-textarea" placeholder="Opcional"></textarea>
                </div>
            `,
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Salvar',
            confirmButtonColor: '#6b63ff',
            cancelButtonColor: '#040414',
            focusConfirm: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: function () {
                let popup = Swal.getPopup();
                $(popup).find('.select2-modal').each(function () {
                    let $select = $(this);
                    $select.select2({
                        dropdownParent: $(popup),
                        width: '100%',
                        placeholder: $select.find('option:first').text(),
                        allowClear: true,
                    });
                });

                if (editando) {
                    carregarDadosEdicao(id, popup);
                }
            },
            preConfirm: function () {
                let cliente = $('#swal-cliente').val();
                let empresa = $('#swal-empresa').val();
                let unidade = $('#swal-unidade').val();
                let valor = $('#swal-valor').val();
                let vencimento = $('#swal-vencimento').val();

                if (!cliente) { Swal.showValidationMessage('Selecione um cliente'); return false; }
                if (!empresa) { Swal.showValidationMessage('Selecione uma empresa'); return false; }
                if (!unidade) { Swal.showValidationMessage('Selecione uma unidade'); return false; }
                if (!valor) { Swal.showValidationMessage('Informe o valor'); return false; }
                if (!vencimento) { Swal.showValidationMessage('Informe o vencimento'); return false; }

                return {
                    id: id || '',
                    idClientes: cliente,
                    idEmpresa: empresa,
                    idUnidade: unidade,
                    idSubUnidade: $('#swal-subunidade').val() || '',
                    idCategoria: $('#swal-categoria').val() || '',
                    valor: valor,
                    vencimento: vencimento,
                    unidade: '',
                    observacoes: $('#swal-obs').val()
                };
            }
        }).then(result => {
            if (result.isConfirmed) {
                let data = new URLSearchParams(result.value);
                fetch(siteUrl + 'contasReceber/salvar', {
                    method: 'POST',
                    body: data,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            Swal.fire('Salvo!', res.message, 'success')
                                .then(() => {
                                    carregarContasReceber();
                                    carregarDashboard();
                                });
                        } else {
                            Swal.fire('Erro', res.message || 'Falha ao salvar.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Erro', 'Erro de conexão.', 'error'));
            }
        });
    });
}

let carregarUnidadesModal = function (empresaId) {
    return carregarUnidades(empresaId, 'swal-unidade').then(() => {
        document.getElementById('swal-subunidade').innerHTML = '<option value="">Selecione uma subunidade</option>';
    });
};

let carregarSubUnidadesModal = function (unidadeId) {
    return carregarSubUnidades(unidadeId, 'swal-subunidade');
};

function carregarDadosEdicao(id, popup) {
    fetch(siteUrl + 'contasReceber/getDados?id=' + id)
        .then(res => res.json())
        .then(d => {
            $('#swal-cliente').val(String(d.idClientes)).trigger('change');
            $('#swal-empresa').val(String(d.idEmpresa)).trigger('change');
            $('#swal-categoria').val(String(d.idCategoria || '')).trigger('change');

            carregarUnidadesModal(d.idEmpresa).then(() => {
                $('#swal-unidade').val(String(d.idUnidade)).trigger('change');
                carregarSubUnidadesModal(d.idUnidade).then(() => {
                    $('#swal-subunidade').val(String(d.idSubUnidade || '')).trigger('change');
                });
            });

            let valorNum = parseFloat(d.valor) || 0;
            $('#swal-valor').val(valorNum.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            $('#swal-vencimento').val(d.vencimento || '');
            $('#swal-obs').val(d.observacoes || '');
        });
}

function editarConta(id) {
    abrirModal(id);
}

function excluirConta(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir esta conta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            let data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'contasReceber/excluir', {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('Excluído!', res.message, 'success');
                    carregarContasReceber();
                    carregarDashboard();
                });
        }
    });
}

function importarCSV(file) {
    if (!file) return;

    let reader = new FileReader();
    reader.onload = function (e) {
        let text = e.target.result;
        let lines = text.split('\n');
        if (lines.length < 2) {
            Swal.fire('Erro', 'CSV vazio ou formato inválido.', 'error');
            return;
        }

        let headers = lines[0].split(';').map(h => h.trim().toLowerCase());
        let required = ['cliente', 'valor', 'vencimento'];
        for (let r of required) {
            if (!headers.includes(r)) {
                Swal.fire('Erro', 'Coluna "' + r + '" não encontrada no CSV.', 'error');
                return;
            }
        }

        let imported = 0;
        let errors = [];

        for (let i = 1; i < lines.length; i++) {
            let line = lines[i].trim();
            if (!line) continue;

            let cols = line.split(';');
            let row = {};
            headers.forEach((h, idx) => {
                row[h] = (cols[idx] || '').trim();
            });

            if (!row.cliente || !row.valor || !row.vencimento) {
                errors.push('Linha ' + (i + 1) + ': dados incompletos');
                continue;
            }

            let data = new URLSearchParams();
            data.append('idClientes', row.cliente);
            data.append('idUsuarios', '');
            data.append('idEmpresa', '');
            data.append('idUnidade', '');
            data.append('idSubUnidade', '');
            data.append('valor', row.valor.replace(',', '.'));
            data.append('vencimento', row.vencimento);
            data.append('unidade', row.unidade || '');
            data.append('idCategoria', row.categoria || '');
            data.append('observacoes', row.observacoes || '');

            fetch(siteUrl + 'contasReceber/salvar', {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.json()).then(res => {
                if (res.success) imported++;
            });
        }

        Swal.fire('Importação concluída', imported + ' conta(s) importada(s).' + (errors.length ? '\n' + errors.length + ' erro(s).' : ''), 'info');
        carregarContasReceber();
        carregarDashboard();
    };
    reader.readAsText(file, 'UTF-8');

    document.getElementById('csvFileInput').value = '';
}

window.carregarUnidadesModal = carregarUnidadesModal;
window.carregarSubUnidadesModal = carregarSubUnidadesModal;
