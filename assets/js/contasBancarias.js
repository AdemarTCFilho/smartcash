function mascaraMonetaria(el) {
    let v = el.value.replace(/\D/g, '');
    if (!v) { el.value = ''; return; }
    v = (parseInt(v) / 100).toFixed(2);
    el.value = v.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function formatReal(v) {
    v = parseFloat(v) || 0;
    return v.toFixed(2).replace('.', ',');
}

function formatCoin(v) {
    return 'R$ ' + formatReal(v);
}

function formatMoney(val) {
    if (!val && val !== 0) return 'R$ 0,00';
    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function carregarContas() {
    fetch(siteUrl + 'contasBancarias/getDados')
        .then(res => res.json())
        .then(r => {
            let saldo = parseFloat(r.saldoConsolidado) || 0;
            let elSaldo = document.getElementById('saldoConsolidado');
            elSaldo.textContent = formatMoney(saldo);
            elSaldo.className = 'value ' + (saldo >= 0 ? 'positivo' : 'negativo');

            let grid = document.getElementById('cardsGrid');
            grid.innerHTML = '';

            if (!r.contas || r.contas.length === 0) {
                grid.innerHTML = '<div class="vazio">Nenhuma conta banc\u00e1ria cadastrada.</div>';
                return;
            }

            r.contas.forEach(c => {
                let saldoConta = parseFloat(c.saldoInicial) || 0;
                let entradas = 0;
                let saidas = 0;
                let saldoFinal = saldoConta + entradas - saidas;
                let classeSaldo = saldoFinal >= 0 ? 'positivo' : 'negativo';

                let card = document.createElement('div');
                card.className = 'conta-card';
                card.innerHTML = '' +
                    '<div class="card-header">' +
                        '<div class="card-nome">' + (c.nome || '-') + '</div>' +
                        '<div class="card-type">' + (c.tipo || '-') + '</div>' +
                    '</div>' +
                    '<div class="card-detalhes">' +
                        '<div><div class="det-label">Banco</div><div class="det-value">' + (c.banco || '-') + '</div></div>' +
                        '<div><div class="det-label">Ag\u00eancia</div><div class="det-value">' + (c.agencia || '-') + '</div></div>' +
                        '<div><div class="det-label">Conta</div><div class="det-value">' + (c.conta || '-') + '</div></div>' +

                    '</div>' +
                    '<div class="card-financeiro">' +
                        '<div class="fin-item"><div class="fin-label">Entradas</div><div class="fin-value entrada">' + formatMoney(entradas) + '</div></div>' +
                        '<div class="fin-item"><div class="fin-label">Sa\u00eddas</div><div class="fin-value saida">' + formatMoney(saidas) + '</div></div>' +
                    '</div>' +
                    '<div class="saldo-destaque ' + classeSaldo + '">' +
                        '<div class="saldo-label">Saldo Atual</div>' +
                        '<div class="saldo-valor">' + formatMoney(saldoFinal) + '</div>' +
                    '</div>' +
                    '<div class="card-actions">' +
                        '<button class="btn btn-sm btn-outline editarConta" data-id="' + c.idContaBancaria + '">Editar</button>' +
                        '<button class="btn btn-sm btn-outline-danger excluirConta" data-id="' + c.idContaBancaria + '">Excluir</button>' +
                    '</div>';
                grid.appendChild(card);
            });
        });
}

function carregarUnidades(empresaId, selectId) {
    let select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Carregando...</option>';
    if (!empresaId) {
        select.innerHTML = '<option value="">Selecione uma unidade</option>';
        return Promise.resolve();
    }
    return fetch(siteUrl + 'contasBancarias/getUnidades?idEmpresa=' + empresaId)
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Selecione uma unidade</option>';
            res.forEach(u => {
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
    return fetch(siteUrl + 'contasBancarias/getSubUnidades?idUnidade=' + unidadeId)
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Selecione uma subunidade</option>';
            res.forEach(s => {
                opts += '<option value="' + s.idSubUnidade + '">' + s.nomeSubUnidade + '</option>';
            });
            select.innerHTML = opts;
        });
}

function abrirModal(id) {
    let editando = !!id;

    Promise.all([
        fetch(siteUrl + 'contasBancarias/getEmpresas').then(r => r.json()),
        fetch(siteUrl + 'contasBancarias/getUnidades').then(r => r.json()),
    ]).then(([empresasRes]) => {
        let empresasOpts = '<option value="">Selecione uma empresa</option>';
        empresasRes.forEach(e => {
            empresasOpts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
        });

        Swal.fire({
            title: editando ? 'Editar Conta Banc\u00e1ria' : 'Nova Conta Banc\u00e1ria',
            html: '' +
                '<div style="text-align:left;">' +
                    '<input type="hidden" id="swal-id" value="' + (id || '') + '" />' +
                    '<div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-top:15px;">' +
                        '<div>' +
                            '<label class="swal-label">Nome da conta *</label>' +
                            '<input id="swal-nome" class="swal-input" placeholder="Ex: Conta Matriz" />' +
                        '</div>' +
                        '<div>' +
                            '<label class="swal-label">Banco *</label>' +
                            '<input id="swal-banco" class="swal-input" placeholder="Ex: Banco do Brasil" />' +
                        '</div>' +
                        '<div>' +
                            '<label class="swal-label">Ag\u00eancia *</label>' +
                            '<input id="swal-agencia" class="swal-input" placeholder="Ex: 1234-5" />' +
                        '</div>' +
                        '<div>' +
                            '<label class="swal-label">Conta *</label>' +
                            '<input id="swal-conta" class="swal-input" placeholder="Ex: 12345-6" />' +
                        '</div>' +
                        '<div>' +
                            '<label class="swal-label">Tipo *</label>' +
                            '<select id="swal-tipo" class="swal-select">' +
                                '<option value="">Selecione</option>' +
                                '<option value="Corrente">Corrente</option>' +
                                '<option value="Caixa">Caixa</option>' +
                                '<option value="Poupan\u00e7a">Poupan\u00e7a</option>' +
                                '<option value="Sal\u00e1rio">Sal\u00e1rio</option>' +
                                '<option value="Investimento">Investimento</option>' +
                                '<option value="Outro">Outro</option>' +
                            '</select>' +
                        '</div>' +
                        '<div>' +
                            '<label class="swal-label">Saldo inicial *</label>' +
                            '<input id="swal-saldo" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)" />' +
                        '</div>' +
                        '<div>' +
                            '<label class="swal-label">Empresa *</label>' +
                            '<select id="swal-empresa" class="swal-select select2-modal" onchange="carregarUnidadesModal(this.value)">' +
                                empresasOpts +
                            '</select>' +
                        '</div>' +
                        '<div>' +
                            '<label class="swal-label">Unidade *</label>' +
                            '<select id="swal-unidade" class="swal-select select2-modal" onchange="carregarSubUnidadesModal(this.value)">' +
                                '<option value="">Carregando...</option>' +
                            '</select>' +
                        '</div>' +
                        '<div>' +
                            '<label class="swal-label">SubUnidade</label>' +
                            '<select id="swal-subunidade" class="swal-select select2-modal">' +
                                '<option value="">Selecione uma subunidade</option>' +
                            '</select>' +
                        '</div>' +
                    '</div>' +
                '</div>',
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Salvar',
            confirmButtonColor: '#5358ee',
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
                let nome = document.getElementById('swal-nome').value;
                let empresa = document.getElementById('swal-empresa').value;
                let unidade = document.getElementById('swal-unidade').value;
                let banco = document.getElementById('swal-banco').value;
                let agencia = document.getElementById('swal-agencia').value;
                let conta = document.getElementById('swal-conta').value;
                let tipo = document.getElementById('swal-tipo').value;
                let saldo = document.getElementById('swal-saldo').value;

                if (!nome) { Swal.showValidationMessage('Informe o nome da conta'); return false; }
                if (!empresa) { Swal.showValidationMessage('Selecione uma empresa'); return false; }
                if (!unidade) { Swal.showValidationMessage('Selecione uma unidade'); return false; }
                if (!banco) { Swal.showValidationMessage('Informe o banco'); return false; }
                if (!agencia) { Swal.showValidationMessage('Informe a ag\u00eancia'); return false; }
                if (!conta) { Swal.showValidationMessage('Informe a conta'); return false; }
                if (!tipo) { Swal.showValidationMessage('Selecione o tipo'); return false; }
                if (!saldo) { Swal.showValidationMessage('Informe o saldo inicial'); return false; }

                return {
                    id: id || '',
                    nome: nome,
                    idEmpresa: empresa,
                    idUnidade: unidade,
                    idSubUnidade: document.getElementById('swal-subunidade').value || '',
                    banco: banco,
                    agencia: agencia,
                    conta: conta,
                    tipo: tipo,
                    saldoInicial: saldo,

                };
            }
        }).then(result => {
            if (result.isConfirmed) {
                let data = new URLSearchParams();
                Object.keys(result.value).forEach(k => data.append(k, result.value[k]));

                let url = editando ? 'contasBancarias/editar' : 'contasBancarias/adicionar';

                fetch(siteUrl + url, {
                    method: 'POST',
                    body: data,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            Swal.fire('Salvo!', res.message, 'success')
                                .then(() => carregarContas());
                        } else {
                            Swal.fire('Erro', res.message || 'Falha ao salvar.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Erro', 'Erro de conex\u00e3o.', 'error'));
            }
        });
    });
}

function carregarDadosEdicao(id, popup) {
    fetch(siteUrl + 'contasBancarias/getConta?id=' + id)
        .then(res => res.json())
        .then(d => {
            document.getElementById('swal-nome').value = d.nome || '';
            document.getElementById('swal-banco').value = d.banco || '';
            document.getElementById('swal-agencia').value = d.agencia || '';
            document.getElementById('swal-conta').value = d.conta || '';
            document.getElementById('swal-tipo').value = d.tipo || '';
            let valorNum = parseFloat(d.saldoInicial) || 0;
            document.getElementById('swal-saldo').value = valorNum.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $('#swal-empresa').val(String(d.idEmpresa)).trigger('change');

            carregarUnidadesModal(d.idEmpresa).then(() => {
                $('#swal-unidade').val(String(d.idUnidade)).trigger('change');
                carregarSubUnidadesModal(d.idUnidade).then(() => {
                    $('#swal-subunidade').val(String(d.idSubUnidade || '')).trigger('change');
                });
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

document.addEventListener('DOMContentLoaded', function () {
    carregarContas();

    document.getElementById('btnNovaConta').addEventListener('click', function () {
        abrirModal();
    });

    document.addEventListener('click', function (e) {
        let btn = e.target.closest('.editarConta');
        if (btn) abrirModal(btn.dataset.id);
    });

    document.addEventListener('click', function (e) {
        let btn = e.target.closest('.excluirConta');
        if (btn) {
            let id = btn.dataset.id;
            Swal.fire({
                title: 'Confirmar exclus\u00e3o',
                text: 'Deseja realmente excluir esta conta banc\u00e1ria?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ff5b67',
            }).then(result => {
                if (result.isConfirmed) {
                    let data = new URLSearchParams();
                    data.append('idContaBancaria', id);
                    fetch(siteUrl + 'contasBancarias/excluir', {
                        method: 'POST',
                        body: data,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(res => res.json())
                        .then(res => {
                            Swal.fire(res.success ? 'Sucesso' : 'Erro', res.message, res.success ? 'success' : 'error');
                            if (res.success) carregarContas();
                        });
                }
            });
        }
    });
});

window.carregarUnidadesModal = carregarUnidadesModal;
window.carregarSubUnidadesModal = carregarSubUnidadesModal;
