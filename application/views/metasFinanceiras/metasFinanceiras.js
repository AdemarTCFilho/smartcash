function formatMoney(val) {
    if (val === null || val === undefined) return 'R$ 0,00';
    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatMoneyInput(v) {
    var num = v.replace(/\D/g, '');
    if (!num) return '0,00';
    num = (parseInt(num) / 100).toFixed(2);
    return num.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseMoneyInput(v) {
    if (!v) return 0;
    var num = v.replace(/[R$\s]/g, '').replace(/\./g, '').replace(',', '.');
    return parseFloat(num) || 0;
}

function mascaraMonetaria(el) {
    var v = el.value.replace(/\D/g, '');
    if (!v) { el.value = ''; return; }
    v = (parseInt(v) / 100).toFixed(2);
    el.value = v.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function gerarMesesOptions() {
    var meses = [];
    var now = new Date();
    for (var i = 0; i < 12; i++) {
        var d = new Date(now.getFullYear(), now.getMonth() - i, 1);
        var label = d.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
        var value = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
        meses.push({ value: value, label: label });
    }
    return meses.map(function (m) { return '<option value="' + m.value + '">' + m.label + '</option>'; }).join('');
}

document.addEventListener('DOMContentLoaded', function () {
    preencherMeses();
    carregarEmpresas();
    carregarDados();

    document.getElementById('periodo-select').addEventListener('change', carregarDados);
    document.getElementById('filtroEmpresa').addEventListener('change', carregarDados);

    document.getElementById('btnNovaMeta').addEventListener('click', function () {
        abrirModal();
    });

    document.getElementById('btnExportarCSV').addEventListener('click', function () {
        var mes = document.getElementById('periodo-select').value;
        var empresa = document.getElementById('filtroEmpresa').value;
        var url = siteUrl + 'metasFinanceiras/exportarCSV?mes=' + mes + '&idEmpresa=' + empresa;
        window.location.href = url;
    });
});

function preencherMeses() {
    var sel = document.getElementById('periodo-select');
    sel.innerHTML = gerarMesesOptions();
    if (sel.options.length > 0) {
        sel.selectedIndex = 0;
    }
}

function carregarEmpresas() {
    fetch(siteUrl + 'metasFinanceiras/listarEmpresas')
        .then(function (r) { return r.json(); })
        .then(function (res) {
            var sel = document.getElementById('filtroEmpresa');
            var opts = '<option value="">Todas as empresas</option>';
            if (res && res.data) {
                res.data.forEach(function (e) {
                    opts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
                });
            }
            sel.innerHTML = opts;
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $(sel).select2({ width: '200px', placeholder: 'Todas as empresas', allowClear: true });
            }
        });
}

function carregarDados() {
    var mes = document.getElementById('periodo-select').value;
    var empresa = document.getElementById('filtroEmpresa').value;
    var params = 'mes=' + mes + '&idEmpresa=' + empresa;

    fetch(siteUrl + 'metasFinanceiras/getDados?' + params)
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function (d) {
            if (d.error) { console.error('API error:', d.error); return; }
            atualizarCards(d.consolidado);
            preencherTabela(d.metas);
        })
        .catch(function (err) {
            console.error('carregarDados error:', err);
            document.getElementById('tabelaMetas').innerHTML =
                '<tr><td colspan="8" style="color:#ff5b67;text-align:center;padding:30px">Erro ao carregar dados: ' + err.message + '</td></tr>';
        });
}

function atualizarCards(c) {
    document.getElementById('cardReceita').textContent = formatMoney(c ? c.totalMetaReceita : 0);
    document.getElementById('cardDespesa').textContent = formatMoney(c ? c.totalTetoDespesa : 0);
    document.getElementById('cardLucro').textContent = formatMoney(c ? c.totalMetaLucro : 0);
}

var coresEmpresa = {};
var coresLista = ['#5865ff', '#42ef83', '#ff5c67', '#ffc82d', '#6671ff'];

function preencherTabela(metas) {
    coresEmpresa = {};
    var idxCor = 0;
    metas.forEach(function (m) {
        if (!coresEmpresa[m.idEmpresa]) {
            coresEmpresa[m.idEmpresa] = coresLista[idxCor++ % coresLista.length];
        }
    });

    var html = '';
    metas.forEach(function (m) {
        var rec = parseFloat(m.metaReceita) || 0;
        var desp = parseFloat(m.tetoDespesa) || 0;
        var lucro = parseFloat(m.metaLucro) || 0;
        var realizado = parseFloat(m.realizadoReceita) || 0;
        var atingimento = rec > 0 ? ((realizado / rec) * 100).toFixed(1) : '0.0';
        var atingClass = atingimento >= 100 ? 'green' : 'red';
        var cor = coresEmpresa[m.idEmpresa] || '#5865ff';

        html += '<tr>' +
            '<td><b>' + (m.nomeUnidade || '—') + '</b></td>' +
            '<td><span class="dot" style="background:' + cor + '"></span>' + (m.nomeEmpresa || '—') + '</td>' +
            '<td>' + formatMoney(rec) + '</td>' +
            '<td>' + formatMoney(desp) + '</td>' +
            '<td class="green">' + formatMoney(lucro) + '</td>' +
            '<td>' + formatMoney(realizado) + '</td>' +
            '<td class="' + atingClass + '">' + atingimento + '%</td>' +
            '<td>' +
                '<span class="edit" onclick="abrirModal(' + m.idProjReal + ')" title="Editar">✎</span>&nbsp;' +
                '<span class="edit" onclick="excluirMeta(' + m.idProjReal + ')" title="Excluir"><i class="fa fa-trash-o" aria-hidden="true"></i></span>' +
            '</td>' +
        '</tr>';
    });

    if (metas.length === 0) {
        html = '<tr><td colspan="8" style="color:#7E89B7;text-align:center;padding:30px">Nenhuma meta cadastrada para o período.</td></tr>';
    }

    document.getElementById('tabelaMetas').innerHTML = html;
}

function abrirModal(id) {
    var editando = !!id;

    function montarForm(empOpts) {
        Swal.fire({
            title: editando ? 'Editar Meta' : 'Nova Meta',
            html: [
                '<div style="text-align:left;">',
                '<div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-top:15px;">',
                '<div><label class="swal-label">Empresa:</label><select id="swal-empresa" class="swal-select select2-modal" onchange="carregarUnidades(this.value)">' + empOpts + '</select></div>',
                '<div><label class="swal-label">Unidade:</label><select id="swal-unidade" class="swal-select select2-modal" onchange="carregarSubUnidades(this.value)"><option value="">Selecione</option></select></div>',
                '<div><label class="swal-label">SubUnidade:</label><select id="swal-subunidade" class="swal-select select2-modal"><option value="">Sem subunidade</option></select></div>',
                '<div><label class="swal-label">Mês referência:</label><select id="swal-mes" class="swal-select"><option value="">Selecione</option>' + gerarMesesOptions() + '</select></div>',
                '<div><label class="swal-label">Meta Receita (R$):</label><input id="swal-receita" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)"></div>',
                '<div><label class="swal-label">Meta Despesa (R$):</label><input id="swal-despesa" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)"></div>',
                '<div><label class="swal-label">Meta Lucro (R$):</label><input id="swal-lucro" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)"></div>',
                '<div></div>',
                '</div>',
                '<label class="swal-label" style="margin-top:15px;">Observações:</label>',
                '<textarea id="swal-obs" class="swal-textarea" placeholder="Opcional"></textarea>',
                '</div>'
            ].join(''),
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
                var popup = Swal.getPopup();
                $(popup).find('.select2-modal').each(function () {
                    var $select = $(this);
                    $select.select2({
                        dropdownParent: $(popup),
                        width: '100%',
                        placeholder: $select.find('option:first').text(),
                        allowClear: true,
                    });
                });
                if (editando) {
                    carregarDadosEdicao(id);
                }
            },
            preConfirm: function () {
                var empresa = document.getElementById('swal-empresa').value;
                var unidade = document.getElementById('swal-unidade').value;
                var mes = document.getElementById('swal-mes').value;
                if (!empresa) { Swal.showValidationMessage('Selecione uma empresa'); return false; }
                if (!unidade) { Swal.showValidationMessage('Selecione uma unidade'); return false; }
                if (!mes) { Swal.showValidationMessage('Selecione o mês de referência'); return false; }
                return {
                    id: id || '',
                    idEmpresa: empresa,
                    idUnidade: unidade,
                    idSubUnidade: document.getElementById('swal-subunidade').value || '',
                    mesReferencia: mes,
                    metaReceita: document.getElementById('swal-receita').value,
                    metaDespesa: document.getElementById('swal-despesa').value,
                    metaLucro: document.getElementById('swal-lucro').value,
                    observacoes: document.getElementById('swal-obs').value
                };
            }
        }).then(function (result) {
            if (result.isConfirmed) {
                var data = new URLSearchParams(result.value);
                fetch(siteUrl + 'metasFinanceiras/salvar', {
                    method: 'POST',
                    body: data,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.success) {
                            Swal.fire('Salvo!', res.message, 'success').then(function () { carregarDados(); });
                        } else {
                            Swal.fire('Erro', res.message || 'Falha ao salvar.', 'error');
                        }
                    })
                    .catch(function () { Swal.fire('Erro', 'Erro de conexão.', 'error'); });
            }
        });
    }

    fetch(siteUrl + 'metasFinanceiras/listarEmpresas')
        .then(function (r) { return r.json(); })
        .then(function (res) {
            var opts = '<option value="">Selecione uma empresa</option>';
            if (res && res.data) {
                res.data.forEach(function (e) {
                    opts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
                });
            }
            montarForm(opts);
        });
}

function carregarDadosEdicao(id) {
    fetch(siteUrl + 'metasFinanceiras/getDadosMeta?id=' + id)
        .then(function (r) { return r.json(); })
        .then(function (d) {
            $('#swal-empresa').val(String(d.idEmpresa)).trigger('change');
            carregarUnidades(d.idEmpresa, function () {
                $('#swal-unidade').val(String(d.idUnidade)).trigger('change');
                carregarSubUnidades(d.idUnidade, function () {
                    $('#swal-subunidade').val(String(d.idSubUnidade || '')).trigger('change');
                });
            });
            document.getElementById('swal-mes').value = d.mesReferencia || '';
            document.getElementById('swal-receita').value = formatMoneyInput(String(d.metaReceita || 0));
            document.getElementById('swal-despesa').value = formatMoneyInput(String(d.tetoDespesa || 0));
            document.getElementById('swal-lucro').value = formatMoneyInput(String(d.metaLucro || 0));
            document.getElementById('swal-obs').value = d.observacoes || '';
        });
}

function carregarUnidades(empresaId, callback) {
    var sel = document.getElementById('swal-unidade');
    sel.innerHTML = '<option value="">Carregando...</option>';
    if (!empresaId) {
        sel.innerHTML = '<option value="">Selecione</option>';
        if (callback) callback();
        return;
    }
    fetch(siteUrl + 'metasFinanceiras/listarUnidades?idEmpresa=' + empresaId)
        .then(function (r) { return r.json(); })
        .then(function (res) {
            var opts = '<option value="">Selecione uma unidade</option>';
            if (res && res.data) {
                res.data.forEach(function (u) {
                    opts += '<option value="' + u.idUnidade + '">' + u.nomeUnidade + '</option>';
                });
            }
            sel.innerHTML = opts;
            if (callback) callback();
        });
}

function carregarSubUnidades(unidadeId, callback) {
    var sel = document.getElementById('swal-subunidade');
    sel.innerHTML = '<option value="">Carregando...</option>';
    if (!unidadeId) {
        sel.innerHTML = '<option value="">Sem subunidade</option>';
        if (callback) callback();
        return;
    }
    fetch(siteUrl + 'metasFinanceiras/listarSubUnidades?idUnidade=' + unidadeId)
        .then(function (r) { return r.json(); })
        .then(function (res) {
            var opts = '<option value="">Sem subunidade</option>';
            if (res && res.data) {
                res.data.forEach(function (s) {
                    opts += '<option value="' + s.idSubUnidade + '">' + s.nomeSubUnidade + '</option>';
                });
            }
            sel.innerHTML = opts;
            if (callback) callback();
        });
}

function excluirMeta(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir esta meta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            var data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'metasFinanceiras/excluir', {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    Swal.fire('Excluído!', res.message, 'success');
                    carregarDados();
                });
        }
    });
}
