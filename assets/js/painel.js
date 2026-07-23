var miniChart = null;
var revenueChart = null;

document.addEventListener('DOMContentLoaded', function () {
    carregarDados();

    document.getElementById('filtroPeriodo') && document.getElementById('filtroPeriodo').addEventListener('change', carregarDados);
    document.getElementById('btnExportarCSV') && document.getElementById('btnExportarCSV').addEventListener('click', function () {
        var p = document.getElementById('filtroPeriodo').value;
        window.location.href = BaseUrl + 'mapos/exportarCSVDashboard?periodo=' + p;
    });

    setInterval(carregarDados, 60000);
});

function formatMoney(val) {
    if (!val && val !== 0) return 'R$ 0,00';
    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    var parts = dateStr.split('-');
    return parts.length === 3 ? parts[2] + '/' + parts[1] + '/' + parts[0] : dateStr;
}

function carregarDados() {
    var p = document.getElementById('filtroPeriodo') ? document.getElementById('filtroPeriodo').value : 'mensal';

    fetch(BaseUrl + 'mapos/getDadosDashboardExecutivo?periodo=' + p)
        .then(function (r) { return r.json(); })
        .then(function (d) {
            document.getElementById('infosGrupo').textContent =
                d.infos.unidades + ' unidades • ' + d.infos.empresas + ' empresas • BRL • atualizado agora';

            document.getElementById('saldoCaixa').textContent = formatMoney(d.saldoCaixa);

            var s = d.saldoComparativo;
            var sinal = s.variacao >= 0 ? '↗' : '↘';
            document.getElementById('saldoVariacao').textContent =
                sinal + ' ' + (s.variacao >= 0 ? '+' : '') + s.variacao + '% vs mês anterior';

            document.getElementById('aPagarHoje').textContent = formatMoney(d.apagarHoje.total);
            document.getElementById('aPagarHojeQtd').textContent = d.apagarHoje.qtd + ' compromissos';

            var elLucro = document.getElementById('lucroLiquido');
            elLucro.textContent = formatMoney(d.lucro.lucro);
            elLucro.className = 'metric-value' + (d.lucro.lucro < 0 ? ' red' : ' green');
            document.getElementById('lucroMargem').textContent = 'Margem ' + d.lucro.margem + '%';

            var elInad = document.getElementById('inadimplencia');
            elInad.textContent = d.inadimplencia.percentual + '%';
            elInad.className = 'metric-value' + (d.inadimplencia.percentual > 10 ? ' red' : (d.inadimplencia.percentual > 0 ? ' yellow' : ' green'));
            document.getElementById('inadimplenciaQtd').textContent =
                d.inadimplencia.qtdVencidos + ' título(s) vencido(s) de ' + formatMoney(d.inadimplencia.totalVencido);

            document.getElementById('projetado30D').textContent = formatMoney(d.projetado.liquido);

            preencherAlertas(d.alertas);
            preencherMeta(d.meta);
            preencherUnidades(d.unidades);
            criarMiniChart(d.saldoMensal);
            criarRevenueChart(d.faturamento);
        });
}

function preencherAlertas(alertas) {
    var c = document.getElementById('alertasLista');
    if (!alertas || alertas.length === 0) {
        c.innerHTML = '<p class="no-alerts">Sem recebíveis vencidos. Tudo em ordem.</p>';
        return;
    }
    var maxAtraso = 0;
    alertas.forEach(function (a) { if (a.diasAtraso > maxAtraso) maxAtraso = a.diasAtraso; });
    var html = '';
    alertas.forEach(function (a) {
        var destaque = a.diasAtraso === maxAtraso && maxAtraso > 0 ? ' alerta-destaque' : '';
        html += '<div class="alerta-item' + destaque + '">' +
            '<strong>' + (a.nomeCliente || '—') + '</strong><br>' +
            '<span class="alerta-venc">Venceu em ' + formatDate(a.vencimento) + '</span><br>' +
            '<span class="alerta-valor">' + formatMoney(a.valor) + '</span> ' +
            '<span class="alerta-dias">' + a.diasAtraso + ' dias</span></div>';
    });
    c.innerHTML = html;
}

function preencherMeta(meta) {
    document.getElementById('metaPercentual').textContent = meta.percentual + '%';
    document.getElementById('metaPercentual').style.color = meta.percentual >= 100 ? '#4ade80' : (meta.percentual >= 50 ? '#f3bf3a' : '#ff5b67');
    document.getElementById('metaBar').style.width = Math.min(meta.percentual, 100) + '%';
    document.getElementById('metaBar').style.background = meta.percentual >= 100 ? '#4ade80' : '#5b6cff';
    document.getElementById('metaDetalhe').innerHTML =
        formatMoney(meta.realizado) + ' DE ' + formatMoney(meta.meta) +
        '<br>Meta = média 3 meses anteriores +5%';
}

function preencherUnidades(unidades) {
    var c = document.getElementById('resultadoUnidades');
    if (!unidades || unidades.length === 0) {
        c.innerHTML = '<div class="unit empty">Nenhum lançamento no mês atual.</div>';
        return;
    }
    var maxRec = 0;
    unidades.forEach(function (u) { if (parseFloat(u.receita) > maxRec) maxRec = parseFloat(u.receita); });
    var html = '';
    unidades.forEach(function (u) {
        var rec = parseFloat(u.receita) || 0, desp = parseFloat(u.despesa) || 0;
        var lucro = rec - desp, margem = rec > 0 ? ((lucro / rec) * 100).toFixed(1) : '0.0';
        var pct = maxRec > 0 ? (rec / maxRec) * 100 : 0;
        var saudavel = lucro >= 0;
        html += '<div class="unit">' +
            '<div class="badge" style="background:' + (saudavel ? '#062d1a' : '#2d0a0a') + ';color:' + (saudavel ? '#4ade80' : '#ff5b67') + '">' +
            (saudavel ? 'SAUDÁVEL' : 'PREOCUPANTE') + '</div>' +
            '<div class="unit-name">' + u.nomeUnidade + '</div>' +
            '<div class="unit-value">' + formatMoney(lucro) + '</div>' +
            '<div class="unit-sub">' + formatMoney(rec) + ' / ' + formatMoney(desp) + ' · Margem ' + margem + '%</div>' +
            '<div class="bar"><div class="bar-fill" style="width:' + pct + '%"></div></div></div>';
    });
    c.innerHTML = html;
}

function criarMiniChart(dados) {
    var ctx = document.getElementById('miniChart').getContext('2d');
    if (miniChart) miniChart.destroy();
    miniChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['', '', '', '', '', ''],
            datasets: [{
                data: dados && dados.length ? dados : [0, 0, 0, 0, 0, 1],
                borderColor: '#5b6cff', borderWidth: 2, pointRadius: 0, tension: 0.4, fill: false,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { x: { display: false }, y: { display: false } },
            responsive: true, maintainAspectRatio: false,
        }
    });
}

function criarRevenueChart(f) {
    var ctx = document.getElementById('revenueChart').getContext('2d');
    if (revenueChart) revenueChart.destroy();
    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: f.labels || [],
            datasets: [{
                data: f.values || [],
                borderColor: '#5b6cff', backgroundColor: 'rgba(91,108,255,.10)',
                fill: true, pointRadius: 0, tension: 0.4,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            responsive: true, maintainAspectRatio: false,
            scales: {
                x: { grid: { display: false }, ticks: { color: '#8792c7', font: { size: 10 } } },
                y: {
                    grid: { color: 'rgba(255,255,255,.08)', borderDash: [4, 4] },
                    ticks: { color: '#8792c7', font: { size: 10 }, callback: function (v) { return 'R$ ' + v.toLocaleString('pt-BR'); } },
                },
            },
        }
    });
}
