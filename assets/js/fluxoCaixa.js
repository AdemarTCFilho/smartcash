var chartBarras = null;
var chartLinha = null;

document.addEventListener('DOMContentLoaded', function () {
    carregarUnidades();
    carregarContasBancarias();
    setDefaultDates();
    carregarCards();
    carregarFluxoDiario();
    carregarGraficos();

    document.getElementById('filtroDataInicio').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroDataFim').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroUnidade').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroConta').addEventListener('change', aplicarFiltros);

    document.getElementById('btnExportarCSV').addEventListener('click', function () {
        var params = getFiltros();
        var qs = Object.keys(params).map(function (k) {
            return k + '=' + encodeURIComponent(params[k]);
        }).join('&');
        window.location.href = siteUrl + 'fluxoCaixa/exportarCSV?' + qs;
    });
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
        idContaBancaria: document.getElementById('filtroConta').value,
    };
}

function aplicarFiltros() {
    carregarCards();
    carregarFluxoDiario();
    carregarGraficos();
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

function carregarUnidades() {
    fetch(siteUrl + 'fluxoCaixa/getUnidades')
        .then(function (r) { return r.json(); })
        .then(function (unidades) {
            var sel = document.getElementById('filtroUnidade');
            var html = '<option value="">Todas as unidades</option>';
            unidades.forEach(function (u) {
                html += '<option value="' + u.idUnidade + '">' + u.nomeUnidade + '</option>';
            });
            sel.innerHTML = html;
            if ($('#filtroUnidade').data('select2')) $('#filtroUnidade').select2('destroy');
            $('#filtroUnidade').select2({
                width: '220px',
                placeholder: 'Todas as unidades',
                allowClear: true,
            }).on('change', aplicarFiltros);
        });
}

function carregarContasBancarias() {
    fetch(siteUrl + 'fluxoCaixa/getContasBancarias')
        .then(function (r) { return r.json(); })
        .then(function (contas) {
            var sel = document.getElementById('filtroConta');
            var html = '<option value="">Todas as contas</option>';
            contas.forEach(function (c) {
                html += '<option value="' + c.idContaBancaria + '">' + c.nome + ' - ' + c.banco + ' (' + c.conta + ')</option>';
            });
            sel.innerHTML = html;
            if ($('#filtroConta').data('select2')) $('#filtroConta').select2('destroy');
            $('#filtroConta').select2({
                width: '220px',
                placeholder: 'Todas as contas',
                allowClear: true,
            }).on('change', aplicarFiltros);
        });
}

function carregarCards() {
    var filtros = getFiltros();
    var qs = Object.keys(filtros).map(function (k) {
        return k + '=' + encodeURIComponent(filtros[k]);
    }).join('&');

    fetch(siteUrl + 'fluxoCaixa/getDados?' + qs)
        .then(function (r) { return r.json(); })
        .then(function (d) {
            document.getElementById('cardSaldoBancario').textContent = formatMoney(d.saldoBancario);
            document.getElementById('cardEntradas').textContent = formatMoney(d.entradas.total);
            document.getElementById('cardSaidas').textContent = formatMoney(d.saidas.total);

            var elResultado = document.getElementById('cardResultado');
            elResultado.textContent = formatMoney(d.resultado);
            elResultado.className = 'value ' + (d.resultado < 0 ? 'red' : 'green');

            document.getElementById('cardAReceber').textContent = formatMoney(d.aReceber.total);
            document.getElementById('cardAReceberQtd').textContent = d.aReceber.qtd + ' t\u00edtulo(s)';
            document.getElementById('cardAPagar').textContent = formatMoney(d.aPagar.total);
            document.getElementById('cardAPagarQtd').textContent = d.aPagar.qtd + ' t\u00edtulo(s)';

            document.getElementById('cardInadimplencia').textContent = formatMoney(d.inadimplencia.total);
            document.getElementById('cardInadimplenciaQtd').textContent = d.inadimplencia.qtd + ' t\u00edtulo(s)';
            document.getElementById('cardContasVencidas').textContent = d.contasVencidas.qtd;
        });
}

function carregarFluxoDiario() {
    var filtros = getFiltros();
    var qs = Object.keys(filtros).map(function (k) {
        return k + '=' + encodeURIComponent(filtros[k]);
    }).join('&');

    fetch(siteUrl + 'fluxoCaixa/getFluxoDiario?' + qs)
        .then(function (r) { return r.json(); })
        .then(function (dados) {
            var html = '';
            if (!dados || dados.length === 0) {
                html = '<tr><td colspan="4" class="panel-sub-vazio">Sem movimentações no período.</td></tr>';
                document.getElementById('totalRegistros').textContent = '';
            } else {
                dados.forEach(function (d) {
                    var classeSaldo = d.saldo < 0 ? 'red' : 'green';
                    html += '<tr>' +
                        '<td>' + formatDate(d.data) + '</td>' +
                        '<td class="green">' + formatMoney(d.entradas) + '</td>' +
                        '<td class="red">' + formatMoney(d.saidas) + '</td>' +
                        '<td class="' + classeSaldo + '">' + formatMoney(d.saldo) + '</td>' +
                        '</tr>';
                });
                document.getElementById('totalRegistros').textContent = dados.length + ' registro(s)';
            }
            document.getElementById('tabelaFluxo').innerHTML = html;
        });
}

function carregarGraficos() {
    var filtros = getFiltros();
    var qs = Object.keys(filtros).map(function (k) {
        return k + '=' + encodeURIComponent(filtros[k]);
    }).join('&');

    fetch(siteUrl + 'fluxoCaixa/getDadosGraficos?' + qs)
        .then(function (r) { return r.json(); })
        .then(function (d) {
            criarGraficoBarras(d);
            criarGraficoLinha(d);
        });
}

function criarGraficoBarras(d) {
    var ctx = document.getElementById('graficoBarras').getContext('2d');
    if (chartBarras) chartBarras.destroy();

    chartBarras = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: d.dias,
            datasets: [
                {
                    label: 'Entradas',
                    data: d.entradas,
                    backgroundColor: 'rgba(0, 255, 157, 0.7)',
                    borderColor: '#00ff9d',
                    borderWidth: 1,
                },
                {
                    label: 'Saídas',
                    data: d.saidas,
                    backgroundColor: 'rgba(255, 91, 103, 0.7)',
                    borderColor: '#ff5b67',
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: '#9aa3d0', font: { size: 11 } },
                },
            },
            scales: {
                x: {
                    ticks: { color: '#9aa3d0', font: { size: 10 } },
                    grid: { color: '#1a2257' },
                },
                y: {
                    ticks: { color: '#9aa3d0', font: { size: 10 }, callback: function (v) { return 'R$ ' + v.toLocaleString('pt-BR'); } },
                    grid: { color: '#1a2257' },
                },
            },
        },
    });
}

function criarGraficoLinha(d) {
    var ctx = document.getElementById('graficoLinha').getContext('2d');
    if (chartLinha) chartLinha.destroy();

    chartLinha = new Chart(ctx, {
        type: 'line',
        data: {
            labels: d.dias,
            datasets: [
                {
                    label: 'Saldo acumulado',
                    data: d.saldoAcumulado,
                    borderColor: '#6b63ff',
                    backgroundColor: 'rgba(107, 99, 255, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 2,
                    pointBackgroundColor: '#6b63ff',
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: '#9aa3d0', font: { size: 11 } },
                },
            },
            scales: {
                x: {
                    ticks: { color: '#9aa3d0', font: { size: 10 } },
                    grid: { color: '#1a2257' },
                },
                y: {
                    ticks: { color: '#9aa3d0', font: { size: 10 }, callback: function (v) { return 'R$ ' + v.toLocaleString('pt-BR'); } },
                    grid: { color: '#1a2257' },
                },
            },
        },
    });
}
