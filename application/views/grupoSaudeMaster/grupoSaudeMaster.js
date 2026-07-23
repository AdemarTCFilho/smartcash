Chart.defaults.color = "#7E89B7";
Chart.defaults.borderColor = "rgba(255,255,255,.05)";

var chartLinha = null;
var chartBarra = null;
var chartDonut = null;

var opcoesMensais = [];
for (var m = 0; m < 12; m++) {
    var d = new Date();
    d.setMonth(d.getMonth() - m);
    var mes = (d.getMonth() + 1).toString().padStart(2, '0');
    var ano = d.getFullYear().toString().slice(-2);
    opcoesMensais.unshift(mes + '/' + ano);
}

var opcoesTrimestrais = [
    { value: 'T1', label: 'T1 (Jul-Set)' },
    { value: 'T2', label: 'T2 (Out-Dez)' },
    { value: 'T3', label: 'T3 (Jan-Mar)' },
    { value: 'T4', label: 'T4 (Abr-Jun)' }
];

document.addEventListener('DOMContentLoaded', function () {
    preencherPeriodoSelect('mensal');
    carregarEmpresas();
    carregarDados();

    document.querySelectorAll('.filters .btn[data-period]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filters .btn[data-period]').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            preencherPeriodoSelect(this.dataset.period);
            var sel = document.getElementById('periodo-select');
            if (this.dataset.period === 'anual') {
                if (sel) sel.style.display = 'none';
            } else {
                if (sel) sel.style.display = '';
            }
            carregarDados();
        });
    });

    document.getElementById('periodo-select') && document.getElementById('periodo-select').addEventListener('change', carregarDados);
    document.getElementById('filtroEmpresa') && document.getElementById('filtroEmpresa').addEventListener('change', carregarDados);
    document.getElementById('btnExportarCSV') && document.getElementById('btnExportarCSV').addEventListener('click', function () {
        var periodo = document.querySelector('.filters .btn.active') ? document.querySelector('.filters .btn.active').dataset.period : 'mensal';
        window.location.href = siteUrl + 'grupoSaudeMaster/exportarCSV?periodo=' + periodo;
    });

    setInterval(carregarDados, 60000);
});

function formatMoney(val) {
    if (val === null || val === undefined) return 'R$ 0,00';
    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function preencherPeriodoSelect(periodo) {
    var sel = document.getElementById('periodo-select');
    if (!sel) return;
    sel.innerHTML = '';
    if (periodo === 'trimestral') {
        opcoesTrimestrais.forEach(function (o) {
            var opt = document.createElement('option');
            opt.value = o.value;
            opt.textContent = o.label;
            sel.appendChild(opt);
        });
    } else if (periodo !== 'anual') {
        opcoesMensais.forEach(function (o) {
            var opt = document.createElement('option');
            opt.textContent = o;
            sel.appendChild(opt);
        });
    }
    sel.selectedIndex = opcoesMensais.length - 1;
}

function carregarEmpresas() {
    fetch(siteUrl + 'rankingReceitasDespesas/listarEmpresas')
        .then(function (r) { return r.json(); })
        .then(function (res) {
            var sel = document.getElementById('filtroEmpresa');
            if (!sel) return;
            var opts = '<option value="">Todas as empresas</option>';
            if (res && res.data) {
                res.data.forEach(function (e) {
                    opts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
                });
            }
            sel.innerHTML = opts;
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $(sel).select2({
                    width: '200px',
                    placeholder: 'Todas as empresas',
                    allowClear: true,
                });
                $(sel).on('change', carregarDados);
            }
        });
}

function getPeriodoParams() {
    var periodo = document.querySelector('.filters .btn.active') ? document.querySelector('.filters .btn.active').dataset.period : 'mensal';
    var params = 'periodo=' + periodo;
    if (periodo === 'mensal' || periodo === 'trimestral') {
        var sel = document.getElementById('periodo-select');
        if (sel && sel.value) {
            if (periodo === 'mensal' && sel.value.match(/^\d{2}\/\d{2}$/)) {
                var parts = sel.value.split('/');
                params += '&mes=20' + parts[1] + '-' + parts[0];
            }
        }
    }
    var emp = document.getElementById('filtroEmpresa');
    if (emp && emp.value) {
        params += '&idEmpresa=' + emp.value;
    }
    return params;
}

function carregarDados() {
    fetch(siteUrl + 'grupoSaudeMaster/getDados?' + getPeriodoParams())
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function (d) {
            if (d.error) { console.error('API error:', d.error); return; }

            document.getElementById('descGrupo').textContent =
                'Consolidado de ' + d.infos.unidades + ' unidades • ' + d.infos.empresas + ' empresas • ' + d.mesLabel;

            document.getElementById('receitaConsolidada').textContent = formatMoney(d.receita);
            document.getElementById('receitaPeriodo').textContent = d.mesLabel;

            document.getElementById('despesaConsolidada').textContent = formatMoney(d.despesa);
            var pctDesp = d.receita > 0 ? ((d.despesa / d.receita) * 100).toFixed(1) : '0.0';
            document.getElementById('despesaPercentual').textContent = pctDesp + '% da receita';

            var elLucro = document.getElementById('lucroConsolidado');
            elLucro.textContent = formatMoney(d.lucro);
            elLucro.className = 'value' + (d.lucro < 0 ? ' red' : '');
            document.getElementById('lucroMargem').textContent = 'Margem ' + d.margem + '%';

            document.getElementById('unidadesAtivas').textContent = d.infos.unidades;
            document.getElementById('empresasControladas').textContent = d.infos.empresas + ' empresas controladas';

            criarGraficoLinha(d.evolucao);
            criarGraficoBarra(d.empresasRD);
            criarGraficoDonut(d.mix);
            preencherIndicadores(d.indicadores);
            preencherTopUnidades(d.topUnidades);
        })
        .catch(function (err) {
            console.error('Erro ao carregar dados:', err);
            document.getElementById('descGrupo').textContent = 'Erro ao carregar dados.';
        });
}

function criarGraficoLinha(e) {
    if (!e) return;
    var ctx = document.getElementById('linha').getContext('2d');
    if (chartLinha) chartLinha.destroy();
    chartLinha = new Chart(ctx, {
        type: 'line',
        data: {
            labels: e.labels,
            datasets: [
                { label: 'Receita', data: e.receitas, borderColor: '#42D67A', backgroundColor: 'rgba(66,214,122,.1)', fill: true, tension: .4, pointRadius: 2 },
                { label: 'Despesa', data: e.despesas, borderColor: '#FF5B6A', backgroundColor: 'rgba(255,91,106,.1)', fill: true, tension: .4, pointRadius: 2 },
                { label: 'Lucro', data: e.lucros, borderColor: '#6172FF', backgroundColor: 'rgba(97,114,255,.15)', fill: true, tension: .4, pointRadius: 2 },
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16, font: { size: 11 } } } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { grid: { color: 'rgba(255,255,255,.05)', borderDash: [4, 4] }, ticks: { font: { size: 10 }, callback: function (v) { return 'R$ ' + v.toLocaleString('pt-BR'); } } },
            }
        }
    });
}

function criarGraficoBarra(rd) {
    if (!rd) return;
    var ctx = document.getElementById('barra').getContext('2d');
    if (chartBarra) chartBarra.destroy();
    chartBarra = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: rd.labels,
            datasets: [
                { label: 'Receita', backgroundColor: '#42D67A', data: rd.receitas, borderRadius: 4 },
                { label: 'Despesa', backgroundColor: '#FF5B6A', data: rd.despesas, borderRadius: 4 },
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16, font: { size: 11 } } } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { grid: { color: 'rgba(255,255,255,.05)', borderDash: [4, 4] }, ticks: { font: { size: 10 }, callback: function (v) { return 'R$ ' + v.toLocaleString('pt-BR'); } } },
            }
        }
    });
}

function criarGraficoDonut(mix) {
    if (!mix) return;
    var ctx = document.getElementById('donut').getContext('2d');
    if (chartDonut) chartDonut.destroy();
    chartDonut = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: mix.labels,
            datasets: [{ data: mix.data, backgroundColor: mix.colors }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } },
                tooltip: { callbacks: { label: function (ctx) { return ctx.label + ': ' + ctx.parsed + '%'; } } }
            }
        }
    });
}

function preencherIndicadores(indicadores) {
    var tbody = document.getElementById('indicadoresTbody');
    if (!indicadores || indicadores.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="color:#7E89B7;text-align:center;padding:30px">Nenhum dado no período.</td></tr>';
        return;
    }
    var cores = ['#5358ee', '#ffc31a', '#4cd676', '#ff5453', '#a855f7', '#22d3ee', '#fb923c', '#34d399'];
    var html = '';
    indicadores.forEach(function (e, i) {
        var rec = parseFloat(e.receita) || 0;
        var desp = parseFloat(e.despesa) || 0;
        var lucro = rec - desp;
        var margem = rec > 0 ? ((lucro / rec) * 100).toFixed(1) : '0.0';
        var cor = cores[i % cores.length];
        html += '<tr>' +
            '<td><i class="fa fa-circle" style="font-size:10px;color:' + cor + '"></i>&nbsp;&nbsp;' + e.nomeEmpresa + '</td>' +
            '<td>' + e.qtdUnidades + '</td>' +
            '<td>' + formatMoney(rec) + '</td>' +
            '<td>' + formatMoney(desp) + '</td>' +
            '<td class="' + (lucro >= 0 ? 'green' : 'red') + ' alinha-direta">' + formatMoney(lucro) + '</td>' +
            '<td class="' + (lucro >= 0 ? 'green' : 'red') + ' alinha-direta">' + margem + '%</td></tr>';
    });
    tbody.innerHTML = html;
}

function preencherTopUnidades(unidades) {
    var tbody = document.getElementById('topUnidadesTbody');
    if (!unidades || unidades.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="color:#7E89B7;text-align:center;padding:30px">Nenhuma unidade no período.</td></tr>';
        return;
    }
    var coresEmpresa = {};
    var cores = ['#5358ee', '#ffc31a', '#4cd676', '#ff5453', '#a855f7', '#22d3ee', '#fb923c', '#34d399'];
    var idx = 0;
    unidades.forEach(function (u) {
        if (!coresEmpresa[u.nomeEmpresa]) coresEmpresa[u.nomeEmpresa] = cores[idx++ % cores.length];
    });
    var html = '';
    unidades.forEach(function (u, i) {
        var rec = parseFloat(u.receita) || 0;
        var desp = parseFloat(u.despesa) || 0;
        var lucro = rec - desp;
        var cor = coresEmpresa[u.nomeEmpresa] || '#5358ee';
        html += '<tr>' +
            '<td class="tamanho-td"><div class="rank-number">' + (i + 1) + '</div></td>' +
            '<td class="tamanho-td-empresa"><strong>' + u.nomeUnidade + '</strong><br/><small class="small"><i class="fa fa-circle" style="font-size:10px;color:' + cor + '"></i>&nbsp;&nbsp;' + u.nomeEmpresa + '</small></td>' +
            '<td class="cor-texto-branco alinha-direta"><p class="sub-texto">RECEITA</p>' + formatMoney(rec) + '</td>' +
            '<td class="green alinha-direta"><p class="sub-texto">LUCRO</p>' + formatMoney(lucro) + '</td></tr>';
    });
    tbody.innerHTML = html;
}
