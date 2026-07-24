document.addEventListener('DOMContentLoaded', function () {
    preencherMeses();
    carregarEmpresas();
    carregarDados();

    $('#periodo-select').on('change', carregarDados);
    $('#filtroEmpresa').on('change', function () {
        carregarUnidades($(this).val(), carregarDados);
    });
    $('#filtroUnidade').on('change', carregarDados);
    $('#btnExportarCSV').on('click', function () {
        var params = getFiltros();
        var qs = Object.keys(params).map(function (k) { return k + '=' + encodeURIComponent(params[k]); }).join('&');
        window.location.href = siteUrl + 'dreGerencial/exportarCSV?' + qs;
    });
});

function getFiltros() {
    return {
        mes: document.getElementById('periodo-select').value,
        idEmpresa: document.getElementById('filtroEmpresa').value,
        idUnidade: document.getElementById('filtroUnidade').value,
    };
}

function formatMoney(val) {
    if (val === null || val === undefined) return 'R$ 0,00';
    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatMoneyParen(val) {
    if (val === null || val === undefined) return 'R$ 0,00';
    var abs = Math.abs(parseFloat(val));
    var fmt = abs.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    return val < 0 ? '(R$ ' + fmt + ')' : 'R$ ' + fmt;
}

function pct(val) {
    if (val === null || val === undefined) return '0,0%';
    return parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + '%';
}

function mesLabel(val) {
    if (!val) return '';
    var parts = val.split('-');
    var meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    return meses[parseInt(parts[1]) - 1] + '/' + parts[0].slice(-2);
}

function gerarMesesOptions() {
    var now = new Date();
    var html = '';
    for (var i = 0; i < 12; i++) {
        var d = new Date(now.getFullYear(), now.getMonth() - i, 1);
        var label = d.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
        var value = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
        html += '<option value="' + value + '">' + label + '</option>';
    }
    return html;
}

function preencherMeses() {
    var sel = document.getElementById('periodo-select');
    sel.innerHTML = gerarMesesOptions();
    if (sel.options.length > 0) sel.selectedIndex = 0;
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $(sel).select2({ width: '160px', placeholder: 'Selecione o mês', allowClear: false });
    }
}

function carregarEmpresas() {
    fetch(siteUrl + 'dreGerencial/listarEmpresas')
        .then(function (r) { return r.json(); })
        .then(function (res) {
            var sel = document.getElementById('filtroEmpresa');
            var html = '<option value="">Todas as empresas</option>';
            if (res && res.data) {
                res.data.forEach(function (e) {
                    html += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
                });
            }
            sel.innerHTML = html;
            if (typeof $ !== 'undefined' && $.fn.select2) {
                if ($(sel).data('select2')) $(sel).select2('destroy');
                $(sel).select2({ width: '180px', placeholder: 'Todas as empresas', allowClear: true });
            }
        });
}

function carregarUnidades(empresaId, callback) {
    var url = siteUrl + 'dreGerencial/listarUnidades';
    if (empresaId) url += '?idEmpresa=' + empresaId;
    fetch(url)
        .then(function (r) { return r.json(); })
        .then(function (res) {
            var sel = document.getElementById('filtroUnidade');
            var html = '<option value="">Todas as unidades</option>';
            if (res && res.data) {
                res.data.forEach(function (u) {
                    html += '<option value="' + u.idUnidade + '">' + u.nomeUnidade + '</option>';
                });
            }
            sel.innerHTML = html;
            if (typeof $ !== 'undefined' && $.fn.select2) {
                if ($(sel).data('select2')) $(sel).select2('destroy');
                $(sel).select2({ width: '200px', placeholder: 'Todas as unidades', allowClear: true });
            }
            if (callback) callback();
        });
}

function carregarDados() {
    var filtros = getFiltros();
    var qs = Object.keys(filtros).map(function (k) { return k + '=' + encodeURIComponent(filtros[k]); }).join('&');

    fetch(siteUrl + 'dreGerencial/getDados?' + qs)
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.error) { console.error('API error:', d.error); return; }
            atualizarCards(d);
            atualizarEstrutura(d);
            atualizarComparativo(d.comparativo);
            atualizarEvolucao(d.evolucao);
        })
        .catch(function (err) {
            console.error('carregarDados error:', err);
        });
}

function atualizarCards(d) {
    document.getElementById('cardReceita').textContent = formatMoney(d.receitas.total);
    document.getElementById('cardReceitaQtd').textContent = d.receitas.qtd + ' lançamento(s)';
    document.getElementById('cardDespesa').textContent = formatMoney(d.despesas.total);
    document.getElementById('cardDespesaQtd').textContent = d.despesas.qtd + ' lançamento(s)';

    var elRes = document.getElementById('cardResultado');
    elRes.textContent = formatMoney(d.resultado);
    elRes.className = 'value ' + (d.resultado < 0 ? 'red' : (d.resultado > 0 ? 'green' : 'branco'));

    document.getElementById('cardMargem').textContent = 'Margem ' + pct(d.margem);
}

function atualizarEstrutura(d) {
    var mes = d.mes;
    var el = document.getElementById('estruturaDre');
    var label = mesLabel(mes);

    var html = '';
    html += '<h3>Estrutura — ' + label + '</h3>';

    // Receitas
    html += '<div class="linha-dre secao"><span class="label">(+) RECEITAS</span><span class="valor green">' + formatMoney(d.receitas.total) + '</span></div>';
    if (d.estrutura.receitas.length === 0) {
        html += '<div class="linha-dre empty"><span>Sem receitas no mês.</span></div>';
    } else {
        d.estrutura.receitas.forEach(function (r) {
            html += '<div class="linha-dre"><span class="label"><span class="dot" style="background:#33e676"></span>' + r.nomeCategoria + '</span><span class="valor">' + formatMoney(r.total) + '</span></div>';
        });
    }
    html += '<div class="linha-dre total"><span>(=) Total de receitas</span><span class="valor green">' + formatMoney(d.receitas.total) + '</span></div>';

    // Despesas
    html += '<div class="linha-dre secao"><span class="label">(-) DESPESAS</span><span class="valor red">' + formatMoneyParen(d.despesas.total) + '</span></div>';
    if (d.estrutura.despesas.length === 0) {
        html += '<div class="linha-dre empty"><span>Sem despesas no mês.</span></div>';
    } else {
        d.estrutura.despesas.forEach(function (r) {
            html += '<div class="linha-dre"><span class="label"><span class="dot" style="background:#ff5b67"></span>' + r.nomeCategoria + '</span><span class="valor">' + formatMoneyParen(r.total) + '</span></div>';
        });
    }
    html += '<div class="linha-dre total"><span>(=) Total de despesas</span><span class="valor red">' + formatMoneyParen(d.despesas.total) + '</span></div>';

    // Resultado
    var classeRes = d.resultado < 0 ? 'red' : (d.resultado > 0 ? 'green' : '');
    html += '<div class="linha-dre total"><span>(=) RESULTADO DO PERÍODO</span><span class="valor ' + classeRes + '">' + formatMoney(d.resultado) + '</span></div>';

    el.innerHTML = html;
}

function atualizarComparativo(c) {
    if (!c) return;

    var el = document.getElementById('tabelaComparativo');

    function varPct(atual, anterior) {
        if (anterior == 0) return atual > 0 ? '100%' : '0%';
        return ((atual - anterior) / Math.abs(anterior) * 100).toLocaleString('pt-BR', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + '%';
    }

    var linhas = [
        { label: 'Receitas', atual: c.receitas.atual, anterior: c.receitas.anterior },
        { label: 'Despesas', atual: c.despesas.atual, anterior: c.despesas.anterior },
        { label: 'Resultado', atual: c.resultado.atual, anterior: c.resultado.anterior },
    ];

    var html = '<tr><th>Conta</th><th>' + mesLabel(c.mesAtual) + '</th><th>' + mesLabel(c.mesAnterior) + '</th><th>Δ</th></tr>';
    linhas.forEach(function (l) {
        var v = varPct(l.atual, l.anterior);
        var vClass = l.atual >= l.anterior ? 'green' : 'red';
        var aClass = l.label === 'Despesas' ? (l.atual <= l.anterior ? 'green' : 'red') : vClass;
        html += '<tr>' +
            '<td>' + l.label + '</td>' +
            '<td>' + formatMoney(l.atual) + '</td>' +
            '<td>' + formatMoney(l.anterior) + '</td>' +
            '<td class="' + aClass + '">' + v + '</td>' +
            '</tr>';
    });

    el.innerHTML = html;
}

function atualizarEvolucao(ev) {
    if (!ev || ev.length === 0) return;

    var el = document.getElementById('tabelaEvolucao');
    var html = '<tr><th>Mês</th><th>Receitas</th><th>Despesas</th><th>Resultado</th></tr>';
    ev.forEach(function (e) {
        var rClass = e.resultado < 0 ? 'red' : (e.resultado > 0 ? 'green' : '');
        html += '<tr>' +
            '<td>' + mesLabel(e.mes) + '</td>' +
            '<td>' + formatMoney(e.receitas) + '</td>' +
            '<td class="red">' + formatMoneyParen(e.despesas) + '</td>' +
            '<td class="' + rClass + '">' + formatMoney(e.resultado) + '</td>' +
            '</tr>';
    });
    el.innerHTML = html;
}
