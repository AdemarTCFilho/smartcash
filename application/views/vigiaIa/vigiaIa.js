var intervalo = null;
var chartLogins = null;

$(document).ready(function () {
    carregarDados();

    $('#periodo-select').on('change', function () {
        var val = $(this).val();
        if (val === 'custom') {
            $('#data-inicio, #data-fim').show();
            carregarDados();
        } else {
            $('#data-inicio, #data-fim').hide();
            carregarDados();
        }
    });

    $('#data-inicio, #data-fim').on('change', function () {
        if ($('#data-inicio').val() && $('#data-fim').val()) {
            carregarDados();
        }
    });

    $('#btnExportarCSV').on('click', function () {
        var params = getParams();
        window.location.href = siteUrl + 'vigiaIa/exportarCSV?' + params;
    });

    iniciarAutoAtualizacao();
});

function getParams() {
    var val = $('#periodo-select').val();
    if (val === 'custom') {
        return 'data_inicio=' + $('#data-inicio').val() + '&data_fim=' + $('#data-fim').val();
    }
    return 'dias=' + val;
}

function carregarDados() {
    var params = getParams();
    $.ajax({
        url: siteUrl + 'vigiaIa/getDados',
        type: 'GET',
        data: params,
        dataType: 'json',
        success: function (r) {
            atualizarIndicadores(r.indicadores);
            atualizarGrafico(r.loginsPorDia);
            $('#tabelaPaises').html('<tr><th>Pa\u00eds</th><th>Acessos</th></tr>' + r.htmlTopPaises);
            $('#tabelaIpsFalhas').html('<tr><th>IP</th><th>Pa\u00eds</th><th>Tentativas</th></tr>' + r.htmlIpsFalhas);
            $('#listaAlertas').html(r.htmlAlertas);
            $('#listaUltimosLogins').html(r.htmlUltimosLogins);
            $('#tabelaBloqueados').html('<tr><th>IP</th><th>Pa\u00eds</th><th>Motivo</th><th>Bloqueado em</th></tr>' + r.htmlBloqueados);
        },
        error: function () {
            console.error('Erro ao carregar dados do Vigia IA');
        }
    });
}

function atualizarIndicadores(ind) {
    if (!ind) return;
    $('#cardTentativas').text(ind.tentativas_bloqueadas ?? 0);
    $('#cardCadastros').text(ind.novos_cadastros ?? 0);
    $('#cardSuspeitos').text(ind.acessos_suspeitos ?? 0);
    $('#cardPaises').text(ind.paises_que_acessaram ?? 0);
}

function atualizarGrafico(dados) {
    if (!dados || dados.length === 0) {
        if (chartLogins) { chartLogins.destroy(); chartLogins = null; }
        return;
    }
    var labels = [];
    var sucesso = [];
    var falha = [];
    dados.forEach(function (d) {
        var parts = d.dia.split('-');
        labels.push(parts[2] + '/' + parts[1]);
        sucesso.push(parseInt(d.sucesso));
        falha.push(parseInt(d.falha));
    });

    var ctx = document.getElementById('graficoLogins').getContext('2d');
    if (chartLogins) { chartLogins.destroy(); }

    chartLogins = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Sucesso',
                    data: sucesso,
                    backgroundColor: 'rgba(51,230,118,0.7)',
                    borderColor: '#33e676',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Falha',
                    data: falha,
                    backgroundColor: 'rgba(255,59,92,0.7)',
                    borderColor: '#ff3b5c',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: '#9aa3d0', font: { size: 12 } }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#9aa3d0' },
                    grid: { color: 'rgba(32,42,104,0.3)' }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#9aa3d0', stepSize: 1 },
                    grid: { color: 'rgba(32,42,104,0.3)' }
                }
            }
        }
    });
}

function iniciarAutoAtualizacao() {
    if (intervalo) clearInterval(intervalo);
    intervalo = setInterval(function () {
        carregarDados();
        var lbl = $('#auto-label');
        lbl.text('atualizando...');
        setTimeout(function () { lbl.text('30s'); }, 1500);
    }, 30000);
}
