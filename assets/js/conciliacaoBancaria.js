document.addEventListener('DOMContentLoaded', function () {
    carregarContas();
    carregarDados();

    document.getElementById('btnNovoLancamento').addEventListener('click', function () {
        abrirModal();
    });

    document.getElementById('btnExportarCSV').addEventListener('click', function () {
        var params = getFiltros();
        var qs = Object.keys(params).map(function (k) {
            return k + '=' + encodeURIComponent(params[k]);
        }).join('&');
        window.location.href = siteUrl + 'conciliacaoBancaria/exportarCSV?' + qs;
    });

    document.getElementById('filtroDataInicio').addEventListener('change', carregarDados);
    document.getElementById('filtroDataFim').addEventListener('change', carregarDados);
    document.getElementById('filtroNaoConciliadas').addEventListener('change', carregarDados);
});

function formatMoney(val) {
    if (!val && val !== 0) return 'R$ 0,00';
    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    var parts = dateStr.split('-');
    if (parts.length !== 3) return dateStr;
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

function mascaraMonetaria(el) {
    var v = el.value.replace(/\D/g, '');
    if (!v) { el.value = ''; return; }
    v = (parseInt(v) / 100).toFixed(2);
    el.value = v.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function getFiltros() {
    return {
        idContaBancaria: document.getElementById('filtroConta').value,
        dataInicio: document.getElementById('filtroDataInicio').value,
        dataFim: document.getElementById('filtroDataFim').value,
        naoConciliadas: document.getElementById('filtroNaoConciliadas').checked ? '1' : '',
    };
}

function carregarContas() {
    fetch(siteUrl + 'conciliacaoBancaria/getContas')
        .then(function (r) { return r.json(); })
        .then(function (contas) {
            var sel = document.getElementById('filtroConta');
            var html = '<option value="">Selecione a conta</option>';
            contas.forEach(function (c) {
                html += '<option value="' + c.idContaBancaria + '">' + c.nome + ' - ' + c.banco + ' (' + c.conta + ')</option>';
            });
            sel.innerHTML = html;

            if ($('#filtroConta').data('select2')) {
                $('#filtroConta').select2('destroy');
            }
            $('#filtroConta').select2({
                width: '300px',
                placeholder: 'Selecione a conta',
                allowClear: true,
            }).on('change', carregarDados);
        });
}

function carregarDados() {
    var filtros = getFiltros();
    if (!filtros.idContaBancaria) {
        document.getElementById('tabelaMovimentacoes').innerHTML = '<tr><td colspan="7" style="text-align:center;color:var(--muted);padding:30px;">Selecione uma conta bancária.</td></tr>';
        document.getElementById('totalEntradas').textContent = 'R$ 0,00';
        document.getElementById('totalSaidas').textContent = 'R$ 0,00';
        document.getElementById('totalSaldo').textContent = 'R$ 0,00';
        document.getElementById('totalRegistros').textContent = '';
        return;
    }

    var qs = Object.keys(filtros).map(function (k) {
        return k + '=' + encodeURIComponent(filtros[k]);
    }).join('&');

    fetch(siteUrl + 'conciliacaoBancaria/getDados?' + qs)
        .then(function (r) { return r.json(); })
        .then(function (r) {
            if (r.resumo) {
                document.getElementById('totalEntradas').textContent = formatMoney(r.resumo.totalEntradas || 0);
                document.getElementById('totalSaidas').textContent = formatMoney(r.resumo.totalSaidas || 0);
                var saldo = (parseFloat(r.resumo.totalEntradas) || 0) - (parseFloat(r.resumo.totalSaidas) || 0);
                if (r.saldoConta) saldo += parseFloat(r.saldoConta.saldoInicial) || 0;
                var elSaldo = document.getElementById('totalSaldo');
                elSaldo.textContent = formatMoney(saldo);
                elSaldo.className = 'value' + (saldo < 0 ? ' red' : '');
            }

            if (!r.movimentacoes || r.movimentacoes.length === 0) {
                document.getElementById('tabelaMovimentacoes').innerHTML = '<tr><td colspan="7" style="text-align:center;color:var(--muted);padding:30px;">Nenhuma movimentação no período.</td></tr>';
                document.getElementById('totalRegistros').textContent = '0 registro(s)';
                return;
            }

            var html = '';
            r.movimentacoes.forEach(function (m) {
                var classeValor = m.tipo === 'Entrada' ? '' : 'red';
                html += '<tr>' +
                    '<td>' + formatDate(m.data) + '</td>' +
                    '<td>' + (m.nomeConta || '-') + '</td>' +
                    '<td>' + (m.descricao || '-') + '</td>' +
                    '<td>' + m.tipo + '</td>' +
                    '<td class="' + classeValor + '">' + formatMoney(m.valor) + '</td>' +
                    '<td>' + (m.conciliada ? 'Sim' : 'Não') + '</td>' +
                    '<td>';
                if (!m.conciliada) {
                    html += '<span class="edit" onclick="conciliarMov(' + m.idConciliacaoBancaria + ')" title="Conciliar" style="color:var(--green);">✓</span> ';
                }
                html += '<span class="edit" onclick="excluirMov(' + m.idConciliacaoBancaria + ')" title="Excluir"><i class="fa fa-trash-o" aria-hidden="true"></i></span>' +
                    '</td></tr>';
            });
            document.getElementById('tabelaMovimentacoes').innerHTML = html;
            document.getElementById('totalRegistros').textContent = r.movimentacoes.length + ' registro(s)';
        });
}

function abrirModal(id) {
    var editando = !!id;

    fetch(siteUrl + 'conciliacaoBancaria/getContas')
        .then(function (r) { return r.json(); })
        .then(function (contas) {
            var contasOpts = '<option value="">Selecione a conta</option>';
            contas.forEach(function (c) {
                contasOpts += '<option value="' + c.idContaBancaria + '">' + c.nome + ' - ' + c.banco + ' (' + c.conta + ')</option>';
            });

            var hoje = new Date().toISOString().slice(0, 10);

            Swal.fire({
                title: editando ? 'Editar Lançamento' : 'Novo Lançamento Manual',
                html: '\
                    <div style="text-align:left;">\
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-top:15px;">\
                            <div>\
                                <label class="swal-label">Conta Bancária *</label>\
                                <select id="swal-conta" class="swal-select select2-modal">\
                                    ' + contasOpts + '\
                                </select>\
                            </div>\
                            <div>\
                                <label class="swal-label">Tipo *</label>\
                                <select id="swal-tipo" class="swal-select select2-modal">\
                                    <option value="Entrada">Entrada</option>\
                                    <option value="Saída">Saída</option>\
                                </select>\
                            </div>\
                            <div>\
                                <label class="swal-label">Valor (R$) *</label>\
                                <input id="swal-valor" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)">\
                            </div>\
                            <div>\
                                <label class="swal-label">Data *</label>\
                                <input id="swal-data" type="date" class="swal-input" value="' + hoje + '">\
                            </div>\
                            <div style="grid-column: span 2;">\
                                <label class="swal-label">Descrição</label>\
                                <textarea id="swal-descricao" class="swal-textarea" placeholder="Descrição da movimentação"></textarea>\
                            </div>\
                        </div>\
                    </div>',
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
                        carregarDadosEdicao(id, popup);
                    }
                },
                preConfirm: function () {
                    var conta = $('#swal-conta').val();
                    var tipo = $('#swal-tipo').val();
                    var valor = $('#swal-valor').val();
                    var data = $('#swal-data').val();

                    if (!conta) { Swal.showValidationMessage('Selecione uma conta'); return false; }
                    if (!valor) { Swal.showValidationMessage('Informe o valor'); return false; }
                    if (!data) { Swal.showValidationMessage('Informe a data'); return false; }

                    return {
                        id: id || '',
                        idContaBancaria: conta,
                        tipo: tipo,
                        valor: valor,
                        data: data,
                        descricao: $('#swal-descricao').val() || '',
                    };
                }
            }).then(function (result) {
                if (result.isConfirmed) {
                    var data = new URLSearchParams(result.value);
                    fetch(siteUrl + 'conciliacaoBancaria/adicionar', {
                        method: 'POST',
                        body: data,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(function (r) { return r.json(); })
                        .then(function (res) {
                            if (res.success) {
                                Swal.fire('Sucesso!', res.message, 'success')
                                    .then(function () { carregarDados(); });
                            } else {
                                Swal.fire('Erro', res.message || 'Falha ao salvar.', 'error');
                            }
                        })
                        .catch(function () { Swal.fire('Erro', 'Erro de conexão.', 'error'); });
                }
            });
        });
}

function conciliarMov(id) {
    Swal.fire({
        title: 'Confirmar conciliação',
        text: 'Deseja marcar esta movimentação como conciliada?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, conciliar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#6b63ff',
    }).then(function (result) {
        if (result.isConfirmed) {
            var data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'conciliacaoBancaria/conciliar', {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        Swal.fire('Conciliado!', res.message, 'success')
                            .then(function () { carregarDados(); });
                    } else {
                        Swal.fire('Erro', res.message, 'error');
                    }
                });
        }
    });
}

function excluirMov(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir esta movimentação?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ff5b67',
    }).then(function (result) {
        if (result.isConfirmed) {
            var data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'conciliacaoBancaria/excluir', {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        Swal.fire('Excluído!', res.message, 'success')
                            .then(function () { carregarDados(); });
                    } else {
                        Swal.fire('Erro', res.message, 'error');
                    }
                });
        }
    });
}

function carregarDadosEdicao(id, popup) {
    fetch(siteUrl + 'conciliacaoBancaria/getDadosById?id=' + id)
        .then(function (r) { return r.json(); })
        .then(function (d) {
            $('#swal-conta').val(String(d.idContaBancaria)).trigger('change');
            $('#swal-tipo').val(d.tipo).trigger('change');
            var valorNum = parseFloat(d.valor) || 0;
            $('#swal-valor').val(valorNum.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            $('#swal-data').val(d.data || '');
            $('#swal-descricao').val(d.descricao || '');
        });
}
