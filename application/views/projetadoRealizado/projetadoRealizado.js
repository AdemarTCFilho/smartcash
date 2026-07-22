document.addEventListener('DOMContentLoaded', function () {

    carregarEmpresas();
    carregarProjReal();
    preencherMeses();

    document.getElementById('btnNovaProjecao').addEventListener('click', function () {
        abrirModal();
    });

    document.getElementById('filtroMes').addEventListener('change', carregarProjReal);
    document.getElementById('filtroEmpresa').addEventListener('change', carregarProjReal);
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

function carregarEmpresas() {
    fetch(siteUrl + 'projetadoRealizado/listarEmpresas')
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
        $(select).trigger('change');
        return Promise.resolve();
    }
    return fetch(siteUrl + 'projetadoRealizado/listarUnidadesPorEmpresa?idEmpresa=' + empresaId)
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Selecione uma unidade</option>';
            res.data.forEach(u => {
                opts += '<option value="' + u.idUnidade + '">' + u.nomeUnidade + '</option>';
            });
            select.innerHTML = opts;
            $(select).trigger('change');
        });
}

function carregarSubUnidades(unidadeId, selectId) {
    let select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Carregando...</option>';
    if (!unidadeId) {
        select.innerHTML = '<option value="">Sem subunidade</option>';
        $(select).trigger('change');
        return Promise.resolve();
    }
    return fetch(siteUrl + 'projetadoRealizado/listarSubUnidadesPorUnidade?idUnidade=' + unidadeId)
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Sem subunidade</option>';
            res.data.forEach(s => {
                opts += '<option value="' + s.idSubUnidade + '">' + s.nomeSubUnidade + '</option>';
            });
            select.innerHTML = opts;
            $(select).trigger('change');
        });
}

function formatMoney(val) {
    if (!val && val !== 0) return 'R$ 0,00';
    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatMoneyInput(v) {
    let num = v.replace(/\D/g, '');
    if (!num) return '0,00';
    num = (parseInt(num) / 100).toFixed(2);
    return num.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseMoneyInput(v) {
    if (!v) return 0;
    let num = v.replace(/[R$\s]/g, '').replace(/\./g, '').replace(',', '.');
    return parseFloat(num) || 0;
}

function carregarProjReal() {
    let mes = document.getElementById('filtroMes').value;
    let empresa = document.getElementById('filtroEmpresa').value;

    fetch(siteUrl + 'projetadoRealizado/listar')
        .then(res => res.json())
        .then(res => {
            let dados = res.data;

            if (mes) dados = dados.filter(d => d.mesReferencia === mes);
            if (empresa) dados = dados.filter(d => String(d.idEmpresa) === String(empresa));

            let html = '';
            dados.forEach(d => {
                html += '<tr>' +
                    '<td>' + (d.nomeUnidade || '—') + '</td>' +
                    '<td>' + (d.nomeEmpresa || '—') + '</td>' +
                    '<td>' + formatMoney(d.receitaProjetada) + '</td>' +
                    '<td>R$ 0,00</td>' +
                    '<td>' + formatMoney(d.metaReceita) + '</td>' +
                    '<td>' + formatMoney(d.despesaProjetada) + '</td>' +
                    '<td>R$ 0,00</td>' +
                    '<td>' + formatMoney(d.tetoDespesa) + '</td>' +
                    '<td>' + (d.observacoes ? d.observacoes.substring(0, 30) : '') + '</td>' +
                    '<td>' + (d.mesReferencia || '') + '</td>' +
                    '<td>' +
                        "<span class='edit' onclick='editarProjReal(" + d.idProjReal + ")' title='Editar'>✎</span>" +
                        "<span class='edit' onclick='excluirProjReal(" + d.idProjReal + ")' title='Excluir'><i class='fa fa-trash-o' aria-hidden='true'></i></span>" +
                    '</td>' +
                '</tr>';
            });
            document.getElementById('tabelaProjReal').innerHTML = html;

            atualizarCards(dados);
            atualizarGridEmpresas(dados);
        });
}

function atualizarCards(dados) {
    let totalRecProj = 0, totalMeta = 0, totalDespProj = 0, totalTeto = 0;
    dados.forEach(d => {
        totalRecProj += parseFloat(d.receitaProjetada) || 0;
        totalMeta += parseFloat(d.metaReceita) || 0;
        totalDespProj += parseFloat(d.despesaProjetada) || 0;
        totalTeto += parseFloat(d.tetoDespesa) || 0;
    });

    let cards = document.querySelectorAll('#cardsResumo .card');
    cards[0].querySelector('.value').textContent = formatMoney(0);
    cards[0].querySelector('.muted').textContent = 'Projetado ' + formatMoney(totalRecProj);

    let percMeta = totalMeta > 0 ? ((0 / totalMeta) * 100).toFixed(1) : 0;
    cards[1].querySelector('.value').textContent = percMeta + '%';
    cards[1].querySelector('.value').className = 'value' + (percMeta < 50 ? ' red' : '');
    cards[1].querySelector('.muted').textContent = percMeta < 50 ? 'Abaixo da meta' : 'Dentro da meta';

    cards[2].querySelector('.value').textContent = formatMoney(0);
    cards[2].querySelector('.muted').textContent = 'Teto ' + formatMoney(totalTeto);

    cards[3].querySelector('.value').textContent = formatMoney(0);
    cards[3].querySelector('.muted').textContent = 'Projetado ' + formatMoney(totalRecProj - totalDespProj);
}

function atualizarGridEmpresas(dados) {
    let empresas = {};
    dados.forEach(d => {
        let id = d.idEmpresa;
        if (!empresas[id]) {
            empresas[id] = { nome: d.nomeEmpresa || '—', recProj: 0, meta: 0, despProj: 0, teto: 0 };
        }
        empresas[id].recProj += parseFloat(d.receitaProjetada) || 0;
        empresas[id].meta += parseFloat(d.metaReceita) || 0;
        empresas[id].despProj += parseFloat(d.despesaProjetada) || 0;
        empresas[id].teto += parseFloat(d.tetoDespesa) || 0;
    });

    let html = '';
    let cores = ['#4cd676', '#ff5453', '#ffc31a', '#5358ee'];
    let i = 0;
    for (let id in empresas) {
        let e = empresas[id];
        let perc = e.meta > 0 ? Math.min((e.recProj / e.meta) * 100, 100) : 0;
        html += '<div class="company">' +
            '<strong class="label"><i class="fa fa-circle" style="font-size:10px;color:' + cores[i % 4] + ';margin-left: -1%;margin-top: 3px;"></i>&nbsp;' + e.nome.toUpperCase() + '</strong>' +
            '<div class="value">' + formatMoney(e.recProj) + '</div>' +
            '<div class="muted">Meta ' + formatMoney(e.meta) + ' · ' + perc.toFixed(0) + '%</div>' +
            '<div class="bar"><div style="width:' + perc + '%"></div></div>' +
            '<div class="green">Resultado R$ 0,00 · proj. ' + formatMoney(e.recProj - e.despProj) + '</div>' +
        '</div>';
        i++;
    }
    document.getElementById('gridEmpresas').innerHTML = html || '<div class="company muted" style="grid-column:1/-1;text-align:center;padding:20px;">Nenhum dado encontrado</div>';
}

function abrirModal(id) {
    let editando = !!id;

    fetch(siteUrl + 'projetadoRealizado/listarEmpresas')
        .then(res => res.json())
        .then(resEmpresas => {
            let empOpts = '<option value="">Selecione uma empresa</option>';
            resEmpresas.data.forEach(e => {
                empOpts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
            });

            Swal.fire({
                title: editando ? 'Editar Projeção' : 'Nova Projeção',
                html: `
                    <div style="text-align:left;">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-top:15px;">
                            <div>
                                <label class="swal-label">Empresa:</label>
                                <select id="swal-empresa" class="swal-select select2-modal" onchange="carregarUnidadesModal(this.value)">
                                    ${empOpts}
                                </select>
                            </div>
                            <div>
                                <label class="swal-label">Unidade:</label>
                                <select id="swal-unidade" class="swal-select select2-modal" onchange="carregarSubUnidadesModal(this.value)">
                                    <option value="">Selecione uma unidade</option>
                                </select>
                            </div>
                            <div>
                                <label class="swal-label">SubUnidade:</label>
                                <select id="swal-subunidade" class="swal-select select2-modal">
                                    <option value="">Sem subunidade</option>
                                </select>
                            </div>
                            <div>
                                <label class="swal-label">Mês de referência:</label>
                                <select id="swal-mes" class="swal-select">
                                    <option value="">Selecione o mês</option>
                                    ${gerarMesesOptions()}
                                </select>
                            </div>
                            <div>
                                <label class="swal-label">Receita projetada (R$):</label>
                                <input id="swal-receita" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)">
                            </div>
                            <div>
                                <label class="swal-label">Meta de receita (R$):</label>
                                <input id="swal-meta" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)">
                            </div>
                            <div>
                                <label class="swal-label">Despesa projetada (R$):</label>
                                <input id="swal-despesa" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)">
                            </div>
                            <div>
                                <label class="swal-label">Teto de despesa (R$):</label>
                                <input id="swal-teto" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)">
                            </div>
                        </div>
                        <label class="swal-label" style="margin-top:15px;">Observações:</label>
                        <textarea id="swal-obs" class="swal-textarea" style="height: 60px !important;" placeholder="Opcional"></textarea>
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
                preConfirm: () => {
                    let empresa = document.getElementById('swal-empresa').value;
                    let unidade = document.getElementById('swal-unidade').value;
                    let mes = document.getElementById('swal-mes').value;
                    let receita = document.getElementById('swal-receita').value;
                    let meta = document.getElementById('swal-meta').value;
                    let despesa = document.getElementById('swal-despesa').value;
                    let teto = document.getElementById('swal-teto').value;

                    if (!empresa) { Swal.showValidationMessage('Selecione uma empresa'); return false; }
                    if (!unidade) { Swal.showValidationMessage('Selecione uma unidade'); return false; }
                    if (!mes) { Swal.showValidationMessage('Selecione o mês de referência'); return false; }

                    return {
                        id: id || '',
                        idEmpresa: empresa,
                        idUnidade: unidade,
                        idSubUnidade: document.getElementById('swal-subunidade').value || '',
                        mesReferencia: mes,
                        receitaProjetada: receita,
                        metaReceita: meta,
                        despesaProjetada: despesa,
                        tetoDespesa: teto,
                        observacoes: document.getElementById('swal-obs').value
                    };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    let data = new URLSearchParams(result.value);
                    fetch(siteUrl + 'projetadoRealizado/salvar', {
                        method: 'POST',
                        body: data,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                Swal.fire('Salvo!', res.message, 'success')
                                    .then(() => carregarProjReal());
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
        let sub = document.getElementById('swal-subunidade');
        sub.innerHTML = '<option value="">Sem subunidade</option>';
        $(sub).trigger('change');
    });
};

let carregarSubUnidadesModal = function (unidadeId) {
    return carregarSubUnidades(unidadeId, 'swal-subunidade');
};

function carregarDadosEdicao(id, popup) {
    fetch(siteUrl + 'projetadoRealizado/getDados?id=' + id)
        .then(res => res.json())
        .then(d => {
            $('#swal-empresa').val(String(d.idEmpresa)).trigger('change');
            carregarUnidadesModal(d.idEmpresa).then(() => {
                $('#swal-unidade').val(String(d.idUnidade)).trigger('change');
                carregarSubUnidadesModal(d.idUnidade).then(() => {
                    $('#swal-subunidade').val(String(d.idSubUnidade || '')).trigger('change');
                });
            });
            $('#swal-mes').val(d.mesReferencia || '');
            $('#swal-receita').val(formatMoneyInput(String(d.receitaProjetada || 0)));
            $('#swal-meta').val(formatMoneyInput(String(d.metaReceita || 0)));
            $('#swal-despesa').val(formatMoneyInput(String(d.despesaProjetada || 0)));
            $('#swal-teto').val(formatMoneyInput(String(d.tetoDespesa || 0)));
            $('#swal-obs').val(d.observacoes || '');
        });
}

function editarProjReal(id) {
    abrirModal(id);
}

function excluirProjReal(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir esta projeção?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            let data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'projetadoRealizado/excluir', {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('Excluído!', res.message, 'success');
                    carregarProjReal();
                });
        }
    });
}

function mascaraMonetaria(el) {
    let v = el.value.replace(/\D/g, '');
    if (!v) { el.value = ''; return; }
    v = (parseInt(v) / 100).toFixed(2);
    el.value = v.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

window.carregarUnidadesModal = carregarUnidadesModal;
window.carregarSubUnidadesModal = carregarSubUnidadesModal;
