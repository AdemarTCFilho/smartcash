document.addEventListener('DOMContentLoaded', function () {
    carregarUnidades();
    carregarCards();
    carregarTabAtiva();

    document.getElementById('filtroDataInicio').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroDataFim').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroUnidade').addEventListener('change', aplicarFiltros);

    document.getElementById('btnExportarCSV').addEventListener('click', function () {
        var aba = document.querySelector('.tab-btn.active');
        var abaId = aba ? aba.getAttribute('onclick').match(/'([^']+)'/)[1] : 'inadimplencia';
        var params = getFiltros();
        params.aba = abaId;
        var qs = Object.keys(params).map(function (k) {
            return k + '=' + encodeURIComponent(params[k]);
        }).join('&');
        window.location.href = siteUrl + 'relatoriosFinanceiros/exportarCSV?' + qs;
    });

    setDefaultDates();
});

function setDefaultDates() {
    var hoje = new Date();
    var primeiroDia = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
    document.getElementById('filtroDataInicio').value = primeiroDia.toISOString().slice(0, 10);
    document.getElementById('filtroDataFim').value = hoje.toISOString().slice(0, 10);
}

function getFiltros() {
    return {
        dataInicio: document.getElementById('filtroDataInicio').value,
        dataFim: document.getElementById('filtroDataFim').value,
        idUnidade: document.getElementById('filtroUnidade').value,
    };
}

function aplicarFiltros() {
    carregarCards();
    carregarTabAtiva();
}

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

function openTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(function (el) { el.classList.remove('active'); });
    document.querySelectorAll('.tab-btn').forEach(function (el) { el.classList.remove('active'); });
    document.getElementById('tab-' + tabId).classList.add('active');
    event.target.classList.add('active');
    carregarAba(tabId);
}

function carregarTabAtiva() {
    var aba = document.querySelector('.tab-btn.active');
    if (aba) {
        var tabId = aba.getAttribute('onclick').match(/'([^']+)'/)[1];
        carregarAba(tabId);
    }
}

function carregarUnidades() {
    fetch(siteUrl + 'relatoriosFinanceiros/getUnidades')
        .then(function (r) { return r.json(); })
        .then(function (unidades) {
            var sel = document.getElementById('filtroUnidade');
            var html = '<option value="">Todas as unidades</option>';
            unidades.forEach(function (u) {
                html += '<option value="' + u.idUnidade + '">' + u.nomeUnidade + '</option>';
            });
            sel.innerHTML = html;

            if ($('#filtroUnidade').data('select2')) {
                $('#filtroUnidade').select2('destroy');
            }
            $('#filtroUnidade').select2({
                width: '250px',
                placeholder: 'Todas as unidades',
                allowClear: true,
            }).on('change', aplicarFiltros);
        });
}

function carregarCards() {
    var filtros = getFiltros();
    var qs = Object.keys(filtros).map(function (k) {
        return k + '=' + encodeURIComponent(filtros[k]);
    }).join('&');

    fetch(siteUrl + 'relatoriosFinanceiros/getDados?' + qs)
        .then(function (r) { return r.json(); })
        .then(function (d) {
            document.getElementById('cardRecebido').textContent = formatMoney(d.recebido.total);
            document.getElementById('cardPago').textContent = formatMoney(d.pago.total);

            var elResultado = document.getElementById('cardResultado');
            elResultado.textContent = formatMoney(d.resultado);
            elResultado.className = 'value ' + (d.resultado < 0 ? 'red' : 'green');
            document.getElementById('cardMargem').textContent = d.margem + '% margem';

            document.getElementById('cardAReceber').textContent = formatMoney(d.aReceber.total);
            document.getElementById('cardAReceberQtd').textContent = d.aReceber.totalContas + ' t\u00edtulo(s)';
            document.getElementById('cardAPagar').textContent = formatMoney(d.aPagar.total);
            document.getElementById('cardAPagarQtd').textContent = d.aPagar.totalContas + ' t\u00edtulo(s)';

            document.getElementById('cardPagarVencidas').textContent = formatMoney(d.pagarVencidas.total);
            document.getElementById('cardPagarVencidasQtd').textContent = d.pagarVencidas.totalContas + ' t\u00edtulo(s)';

            document.getElementById('cardInadimplencia').textContent = formatMoney(d.inadimplencia.total);
            document.getElementById('cardInadimplenciaQtd').textContent = d.inadimplencia.totalContas + ' t\u00edtulo(s)';

            var elSaldo = document.getElementById('cardSaldoPeriodo');
            elSaldo.textContent = formatMoney(d.saldoPeriodo);
            elSaldo.className = 'value ' + (d.saldoPeriodo < 0 ? 'red' : 'green');
        });
}

function carregarAba(tabId) {
    var filtros = getFiltros();
    var qs = Object.keys(filtros).map(function (k) {
        return k + '=' + encodeURIComponent(filtros[k]);
    }).join('&');

    switch (tabId) {
        case 'inadimplencia':
            fetch(siteUrl + 'relatoriosFinanceiros/getInadimplencia?' + qs)
                .then(function (r) { return r.json(); })
                .then(function (dados) { preencherTabelaInadimplencia(dados); });
            break;
        case 'contas_receber':
            fetch(siteUrl + 'relatoriosFinanceiros/getContasReceber?' + qs)
                .then(function (r) { return r.json(); })
                .then(function (dados) { preencherTabelaSimples(dados, 'tabelaContasReceber', ['nomeCliente', 'vencimento', 'valor', 'status']); });
            break;
        case 'contas_pagar':
            fetch(siteUrl + 'relatoriosFinanceiros/getContasPagar?' + qs)
                .then(function (r) { return r.json(); })
                .then(function (dados) { preencherTabelaSimples(dados, 'tabelaContasPagar', ['nomeCliente', 'vencimento', 'valor', 'status']); });
            break;
        case 'historico_pagamentos':
            fetch(siteUrl + 'relatoriosFinanceiros/getHistoricoPagamentos?' + qs)
                .then(function (r) { return r.json(); })
                .then(function (dados) { preencherTabelaSimples(dados, 'tabelaHistoricoPagamentos', ['nomeCliente', 'vencimento', 'valorPago', 'dataPagamento'], 'Valor'); });
            break;
        case 'historico_recebimentos':
            fetch(siteUrl + 'relatoriosFinanceiros/getHistoricoRecebimentos?' + qs)
                .then(function (r) { return r.json(); })
                .then(function (dados) { preencherTabelaSimples(dados, 'tabelaHistoricoRecebimentos', ['nomeCliente', 'vencimento', 'valorRecebido', 'dataRecebimento'], 'Valor'); });
            break;
        case 'por_unidade':
            fetch(siteUrl + 'relatoriosFinanceiros/getPorUnidade?' + qs)
                .then(function (r) { return r.json(); })
                .then(function (dados) { preencherTabelaUnidade(dados); });
            break;
        case 'por_categoria':
            fetch(siteUrl + 'relatoriosFinanceiros/getReceitasPorCategoria?' + qs)
                .then(function (r) { return r.json(); })
                .then(function (dados) { preencherTabelaSimples(dados, 'tabelaReceitasCategoria', ['nomeCategoria', 'total']); });
            fetch(siteUrl + 'relatoriosFinanceiros/getDespesasPorCategoria?' + qs)
                .then(function (r) { return r.json(); })
                .then(function (dados) { preencherTabelaSimples(dados, 'tabelaDespesasCategoria', ['nomeCategoria', 'total']); });
            break;
        case 'top_clientes':
            fetch(siteUrl + 'relatoriosFinanceiros/getTopClientes?' + qs)
                .then(function (r) { return r.json(); })
                .then(function (dados) { preencherTabelaSimples(dados, 'tabelaTopClientes', ['nomeCliente', 'total']); });
            break;
        case 'top_fornecedores':
            fetch(siteUrl + 'relatoriosFinanceiros/getTopFornecedores?' + qs)
                .then(function (r) { return r.json(); })
                .then(function (dados) { preencherTabelaSimples(dados, 'tabelaTopFornecedores', ['nomeCliente', 'total']); });
            break;
    }
}

function preencherTabelaInadimplencia(dados) {
    var html = '';
    if (!dados || dados.length === 0) {
        html = '<tr><td colspan="4" class="panel-sub-vazio">Nenhum título vencido.</td></tr>';
    } else {
        dados.forEach(function (d) {
            var venc = new Date(d.vencimento);
            var hoje = new Date();
            var diff = Math.floor((hoje - venc) / (1000 * 60 * 60 * 24));
            var diasAtraso = diff > 0 ? diff : 0;
            var valorAberto = parseFloat(d.valor) - parseFloat(d.valorRecebido || 0);
            html += '<tr>' +
                '<td>' + (d.nomeCliente || '-') + '</td>' +
                '<td>' + formatDate(d.vencimento) + '</td>' +
                '<td>' + diasAtraso + ' dia(s)</td>' +
                '<td>' + formatMoney(valorAberto > 0 ? valorAberto : d.valor) + '</td>' +
                '</tr>';
        });
    }
    document.getElementById('tabelaInadimplencia').innerHTML = html;
}

function preencherTabelaSimples(dados, tabelaId, campos) {
    var html = '';
    if (!dados || dados.length === 0) {
        var colspan = campos.length;
        html = '<tr><td colspan="' + colspan + '" class="panel-sub-vazio">Nenhum lançamento encontrado.</td></tr>';
    } else {
        dados.forEach(function (d) {
            html += '<tr>';
            campos.forEach(function (c) {
                var val = d[c] !== null && d[c] !== undefined ? d[c] : '-';
                if (c === 'vencimento' || c === 'dataPagamento' || c === 'dataRecebimento') {
                    val = formatDate(val);
                } else if (c === 'valor' || c === 'valorPago' || c === 'valorRecebido' || c === 'total') {
                    val = formatMoney(val);
                }
                html += '<td>' + val + '</td>';
            });
            html += '</tr>';
        });
    }
    document.getElementById(tabelaId).innerHTML = html;
}

function preencherTabelaUnidade(dados) {
    var html = '';
    if (!dados || dados.length === 0) {
        html = '<tr><td colspan="5" class="panel-sub-vazio">Sem dados.</td></tr>';
    } else {
        dados.forEach(function (d) {
            var receita = parseFloat(d.receita) || 0;
            var despesa = parseFloat(d.despesa) || 0;
            var resultado = receita - despesa;
            var margem = receita > 0 ? ((resultado / receita) * 100).toFixed(1) : '0,0';
            html += '<tr>' +
                '<td>' + (d.nomeUnidade || '-') + '</td>' +
                '<td>' + formatMoney(receita) + '</td>' +
                '<td>' + formatMoney(despesa) + '</td>' +
                '<td class="' + (resultado < 0 ? 'red' : '') + '">' + formatMoney(resultado) + '</td>' +
                '<td>' + margem + '%</td>' +
                '</tr>';
        });
    }
    document.getElementById('tabelaPorUnidade').innerHTML = html;
}
