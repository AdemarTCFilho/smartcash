document.addEventListener('DOMContentLoaded', function () {
    carregarEmpresas();
    atualizarPeriodoSelect();
    carregarDados();

    document.querySelectorAll('.filters .btn[data-period]').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filters .btn[data-period]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            atualizarPeriodoSelect();
            carregarDados();
        });
    });

    document.getElementById('periodo-select').addEventListener('change', carregarDados);

    document.querySelectorAll('.view-toggle .btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.view-toggle .btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            carregarDados();
        });
    });
});

function carregarEmpresas() {
    fetch(siteUrl + 'rankingReceitasDespesas/listarEmpresas')
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Todas as empresas</option>';
            res.data.forEach(e => {
                opts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
            });
            let select = document.getElementById('filtroEmpresa');
            select.innerHTML = opts;
            $(select).select2({
                width: '200px',
                placeholder: 'Todas as empresas',
                allowClear: true,
            });
            $(select).on('change', carregarDados);
        });
}

function atualizarPeriodoSelect() {
    let tipo = document.querySelector('.filters .btn[data-period].active').dataset.period;
    let select = document.getElementById('periodo-select');

    if (tipo === 'anual') {
        select.style.display = 'none';
        return;
    }

    select.style.display = '';
    select.innerHTML = '';

    let now = new Date();
    if (tipo === 'mensal') {
        for (let i = 11; i >= 0; i--) {
            let d = new Date(now.getFullYear(), now.getMonth() - i, 1);
            let val = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
            let label = d.toLocaleDateString('pt-BR', { month: 'short', year: '2-digit' }).replace(' ', '/');
            let opt = document.createElement('option');
            opt.value = val;
            opt.textContent = label;
            select.appendChild(opt);
        }
        select.value = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
    } else if (tipo === 'trimestral') {
        let anoAtual = now.getFullYear();
        for (let a = anoAtual; a >= anoAtual - 2; a--) {
            for (let t = 4; t >= 1; t--) {
                let meses = ['Jan–Mar', 'Abr–Jun', 'Jul–Set', 'Out–Dez'];
                let opt = document.createElement('option');
                opt.value = 'T' + t + '/' + a;
                opt.textContent = meses[t - 1] + '/' + a;
                select.appendChild(opt);
            }
        }
        let tAtual = Math.floor(now.getMonth() / 3) + 1;
        select.value = 'T' + tAtual + '/' + anoAtual;
    }
}

function getFiltros() {
    let tipo = document.querySelector('.filters .btn[data-period].active').dataset.period;
    let periodo = document.getElementById('periodo-select').value;
    if (tipo === 'anual') {
        periodo = new Date().getFullYear();
    }
    let idEmpresa = document.getElementById('filtroEmpresa').value;
    let visao = document.querySelector('.view-toggle .btn.active').dataset.visao;
    return { tipoPeriodo: tipo, periodo, idEmpresa, visao };
}

function carregarDados() {
    let f = getFiltros();
    let params = new URLSearchParams(f);

    fetch(siteUrl + 'rankingReceitasDespesas/getDados?' + params.toString())
        .then(res => res.json())
        .then(res => {
            atualizarResumo(res.resumo, f);
            atualizarTabela(res.dados, f.visao);
            atualizarRankings(res.rankingReceitas, res.rankingDespesas);
            desenharGraficos(res.dados, f.visao);
        })
        .catch(() => {
            document.getElementById('painel-principal').innerHTML = '<div class="panel-sub-vazio">Erro ao carregar dados.</div>';
        });
}

function formatMoney(val) {
    if (!val && val !== 0) return 'R$ 0,00';
    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function atualizarResumo(resumo, filtros) {
    document.getElementById('totalReceitas').textContent = formatMoney(resumo.totalReceitas);
    document.getElementById('totalReceitasContas').textContent = resumo.totalContasReceber + ' lançamento(s)';

    document.getElementById('totalDespesas').textContent = formatMoney(resumo.totalDespesas);
    document.getElementById('totalDespesasContas').textContent = resumo.totalContasPagar + ' lançamento(s)';

    document.getElementById('saldo').textContent = formatMoney(resumo.saldo);
    let elSaldo = document.getElementById('saldo');
    elSaldo.className = 'value ' + (resumo.saldo >= 0 ? '' : 'neg');

    document.getElementById('totalLancamentos').textContent = resumo.totalContasReceber + resumo.totalContasPagar + ' registro(s)';

    let entityCount = document.querySelectorAll('#tabelaDados tr').length;
    document.getElementById('totalRegistros').textContent = entityCount || '—';

    let periodoLabel = '';
    if (filtros.tipoPeriodo === 'mensal') {
        let parts = filtros.periodo.split('-');
        let d = new Date(parts[0], parts[1] - 1);
        periodoLabel = d.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
    } else if (filtros.tipoPeriodo === 'trimestral') {
        periodoLabel = filtros.periodo.replace('/', ' ');
    } else {
        periodoLabel = 'Ano ' + filtros.periodo;
    }
    document.getElementById('periodoLabel').textContent = periodoLabel;
}

function atualizarTabela(dados, visao) {
    document.getElementById('thCol1').textContent = visao === 'empresa' ? 'EMPRESA' : 'UNIDADE';

    let html = '';
    let totalReceita = 0, totalDespesa = 0, totalSaldo = 0;

    dados.forEach(d => {
        totalReceita += parseFloat(d.receita) || 0;
        totalDespesa += parseFloat(d.despesa) || 0;
        let saldo = parseFloat(d.saldo) || 0;
        totalSaldo += saldo;

        let nomeCol = visao === 'empresa' ? (d.nomeEmpresa || '—') : (d.nomeUnidade || '—');
        let empresaCol = visao === 'empresa' ? '—' : (d.nomeEmpresa || '—');

        html += '<tr>' +
            '<td>' + nomeCol + '</td>' +
            '<td>' + empresaCol + '</td>' +
            '<td>' + formatMoney(d.receita) + '</td>' +
            '<td>' + formatMoney(d.despesa) + '</td>' +
            '<td class="' + (saldo >= 0 ? 'pos' : 'neg') + '">' + formatMoney(saldo) + '</td>' +
        '</tr>';
    });

    if (!html) {
        html = '<tr><td colspan="5" class="panel-sub-vazio">Nenhum lançamento encontrado no período selecionado.</td></tr>';
    } else {
        html += '<tr style="font-weight:700;border-top:2px solid var(--border)">' +
            '<td>TOTAL</td><td></td>' +
            '<td>' + formatMoney(totalReceita) + '</td>' +
            '<td>' + formatMoney(totalDespesa) + '</td>' +
            '<td class="' + (totalSaldo >= 0 ? 'pos' : 'neg') + '">' + formatMoney(totalSaldo) + '</td>' +
        '</tr>';
    }

    document.getElementById('tabelaDados').innerHTML = html;
    document.getElementById('totalRegistrosDetalhe').textContent = dados.length + ' registro(s)';
}

function atualizarRankings(rankingReceitas, rankingDespesas) {
    let receitasHtml = '';
    if (rankingReceitas.length === 0) {
        receitasHtml = '<div class="panel-sub-vazio">Nenhum lançamento encontrado.</div>';
    } else {
        rankingReceitas.forEach((r, i) => {
            let medalha = i === 0 ? '🥇' : i === 1 ? '🥈' : i === 2 ? '🥉' : (i + 1);
            receitasHtml += '<div class="rank-item">' +
                '<div class="rank-left">' +
                    '<span class="rank-number">' + medalha + '</span>' +
                    '<div class="rank-info">' +
                        '<div class="rank-name">' + r.nomeUnidade + '</div>' +
                        '<div class="rank-empresa">' + r.nomeEmpresa + '</div>' +
                    '</div>' +
                '</div>' +
                '<span class="rank-value" style="color:var(--green)">' + formatMoney(r.total) + '</span>' +
            '</div>';
        });
    }
    document.getElementById('rankingReceitas').innerHTML = receitasHtml;

    let despesasHtml = '';
    if (rankingDespesas.length === 0) {
        despesasHtml = '<div class="panel-sub-vazio">Nenhum lançamento encontrado.</div>';
    } else {
        rankingDespesas.forEach((r, i) => {
            let medalha = i === 0 ? '🥇' : i === 1 ? '🥈' : i === 2 ? '🥉' : (i + 1);
            despesasHtml += '<div class="rank-item">' +
                '<div class="rank-left">' +
                    '<span class="rank-number">' + medalha + '</span>' +
                    '<div class="rank-info">' +
                        '<div class="rank-name">' + r.nomeUnidade + '</div>' +
                        '<div class="rank-empresa">' + r.nomeEmpresa + '</div>' +
                    '</div>' +
                '</div>' +
                '<span class="rank-value" style="color:var(--red)">' + formatMoney(r.total) + '</span>' +
            '</div>';
        });
    }
    document.getElementById('rankingDespesas').innerHTML = despesasHtml;
}

let chartBar, chartDoughnut;

function desenharGraficos(dados, visao) {
    let labels = [];
    let receitas = [];
    let despesas = [];

    dados.forEach(d => {
        let nome = visao === 'empresa' ? (d.nomeEmpresa || '—') : (d.nomeUnidade || '—');
        labels.push(nome);
        receitas.push(parseFloat(d.receita) || 0);
        despesas.push(parseFloat(d.despesa) || 0);
    });

    if (labels.length === 0) {
        labels = ['Sem dados'];
        receitas = [0];
        despesas = [0];
    }

    let cores = ['#6172FF', '#F4C542', '#FF5B6A', '#42D67A', '#a855f7', '#14b8a6', '#f97316', '#6366f1'];

    if (chartBar) chartBar.destroy();
    if (chartDoughnut) chartDoughnut.destroy();

    chartBar = new Chart(document.getElementById('chartBar'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Receita', backgroundColor: '#42D67A', data: receitas },
                { label: 'Despesa', backgroundColor: '#FF5B6A', data: despesas },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { color: '#7E89B7', font: { size: 11 } } }
            },
            scales: {
                x: { ticks: { color: '#7E89B7', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,.05)' } },
                y: { ticks: { color: '#7E89B7', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,.05)' } }
            }
        }
    });

    let totais = labels.map((l, i) => receitas[i] - despesas[i]);

    chartDoughnut = new Chart(document.getElementById('chartDoughnut'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: totais.map(v => Math.abs(v)),
                backgroundColor: labels.map((_, i) => cores[i % cores.length]),
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { color: '#7E89B7', font: { size: 11 } } }
            }
        }
    });
}

function exportarCSV() {
    let f = getFiltros();
    let params = new URLSearchParams(f);
    window.open(siteUrl + 'rankingReceitasDespesas/exportarCSV?' + params.toString(), '_blank');
}

function exportarPDF() {
    window.print();
}
