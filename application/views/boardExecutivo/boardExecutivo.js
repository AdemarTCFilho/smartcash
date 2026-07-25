var boardId = null;
var pollTimer = null;
var executivos = {};
var metricas = {};

$(document).ready(function () {
    carregarDadosIniciais();

    $('#btnConvocar').on('click', function () {
        var pauta = $('#inputPauta').val().trim();
        if (!pauta) { alert('Informe a pauta para convocar o board.'); return; }
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Convocando...');
        $.ajax({
            url: siteUrl + 'boardExecutivo/convocar',
            type: 'POST',
            data: { pauta: pauta },
            dataType: 'json',
            success: function (r) {
                if (r.erro) { alert(r.erro); $('#btnConvocar').prop('disabled', false).html('<i class="fa fa-bullhorn"></i> Convocar Board'); return; }
                boardId = r.id;
                iniciarBoard();
            },
            error: function () {
                alert('Erro ao convocar board.');
                $('#btnConvocar').prop('disabled', false).html('<i class="fa fa-bullhorn"></i> Convocar Board');
            }
        });
    });

    $('#btnExportarCSV').on('click', function () {
        if (boardId) window.location.href = siteUrl + 'boardExecutivo/exportarCSV?id=' + boardId;
    });
});

function carregarDadosIniciais() {
    $.ajax({
        url: siteUrl + 'boardExecutivo/dadosIniciais',
        type: 'GET',
        dataType: 'json',
        success: function (r) {
            executivos = r.executivos || {};
            metricas = r.metricas || {};
            renderMetricasVivas(metricas);
            renderHistorico(r.historico || []);
            $('#totalExecutivos').text(Object.keys(executivos).length);
        }
    });
}

function renderMetricasVivas(m) {
    var html = '';
    html += '<div class="indicador-chip"><i class="fa fa-shield"></i> <strong>' + (m.tentativas_bloqueadas || 0) + '</strong> Tentativas Bloqueadas</div>';
    html += '<div class="indicador-chip"><i class="fa fa-exclamation-triangle"></i> <strong>' + (m.acessos_suspeitos || 0) + '</strong> Acessos Suspeitos</div>';
    html += '<div class="indicador-chip"><i class="fa fa-globe"></i> <strong>' + (m.paises_que_acessaram || 0) + '</strong> Países</div>';
    html += '<div class="indicador-chip"><i class="fa fa-bell"></i> <strong>' + (m.totalAlertas || 0) + '</strong> Alertas</div>';
    $('#indicadoresVivos').html(html);
}

function iniciarBoard() {
    $('#painelPauta').slideUp();
    $('#painelBoard').slideDown();
    $('#btnExportarCSV').show();
    $('#transcricao').empty();
    $('#resumoArea').html('<div class="resumo-vazio"><i class="fa fa-spinner fa-spin"></i> Carregando deliberação...</div>');

    renderExecutivos();

    $('#rodadaLabel').text('Parecer Inicial');
    carregarStatus();
    if (pollTimer) clearInterval(pollTimer);
    pollTimer = setInterval(carregarStatus, 3000);
}

function renderExecutivos() {
    var ord = ['CFO', 'COO', 'CMO', 'CHRO', 'CEO'];
    var html = '';
    ord.forEach(function (s) {
        var e = executivos[s];
        if (!e) return;
        html += '<div class="exec-chip" id="exec-' + s + '" style="background:' + e.cor + '">' +
            '<i class="fa ' + (e.icone || 'fa-user') + '"></i> ' + s +
            ' <span class="exec-falou" id="falou-' + s + '">0</span>' +
            '</div>';
    });
    $('#boardExecs').html(html);
}

function carregarStatus() {
    if (!boardId) return;
    $.ajax({
        url: siteUrl + 'boardExecutivo/status',
        type: 'GET',
        data: { id: boardId },
        dataType: 'json',
        success: function (r) {
            if (r.erro) return;
            renderFalas(r.falas || [], r.executivos || {});
            var delib = r.deliberacao;
            if (delib) {
                if (delib.status === 'concluido') {
                    $('#statusLabel').text('Concluído').css('color', 'var(--green)');
                    if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
                    $('#btnConvocar').prop('disabled', false).html('<i class="fa fa-bullhorn"></i> Convocar Board');
                }
                renderResumo(r);
            }
        }
    });
}

function renderFalas(falas, execs) {
    var ordRodada = ['', 'Parecer Inicial', 'Réplica', 'Decisão Final'];
    var container = $('#transcricao');
    var atualCount = container.children('.fala-item, .rodada-divider').length;
    var falasCount = 0;

    falas.forEach(function (f, idx) {
        var e = execs[f.executivo_sigla] || {};
        var rodadaNome = ordRodada[f.rodada] || 'Rodada ' + f.rodada;
        var cor = e.cor || '#5358ee';
        var icone = e.icone || 'fa-user';
        var nome = e.nome || f.executivo_sigla;
        var cargo = e.cargo || '';

        // Divider de rodada
        var dividerKey = 'divider-' + f.rodada;
        if ($('#' + dividerKey).length === 0 && idx < 3) {
            var dividerHtml = '<div class="rodada-divider" id="' + dividerKey + '">' + rodadaNome + '</div>';
            if (idx === 0) container.append(dividerHtml);
            else container.append(dividerHtml);
        }

        var key = 'fala-' + f.id;
        if ($('#' + key).length === 0) {
            var ts = f.created_at ? new Date(f.created_at.replace(' ', 'T')).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) : '';
            var html = '<div class="fala-item" id="' + key + '">' +
                '<div class="fala-avatar" style="background:' + cor + '">' + f.executivo_sigla + '</div>' +
                '<div class="fala-body">' +
                '<div class="fala-header"><span class="fala-nome" style="color:' + cor + '">' + nome + '</span><span class="fala-cargo">' + cargo + '</span></div>' +
                '<div class="fala-texto">' + formatarFala(f.fala) + '</div>' +
                '<div class="fala-tempo">' + ts + '</div>' +
                '</div></div>';
            container.append(html);

            // Atualizar contador de falas do executivo
            var $falou = $('#falou-' + f.executivo_sigla);
            if ($falou.length) $falou.text(parseInt($falou.text()) + 1);

            falasCount++;

            // Destacar executivo
            $('.exec-chip').removeClass('ativo pulando');
            $('#exec-' + f.executivo_sigla).addClass('ativo pulando');
            setTimeout(function () {
                $('#exec-' + f.executivo_sigla).removeClass('pulando');
            }, 600);

            container.scrollTop(container[0].scrollHeight);
        }
    });

    // Atualizar rodada label
    if (falas.length > 0) {
        var ultimaRodada = falas[falas.length - 1].rodada;
        $('#rodadaLabel').text(ordRodada[ultimaRodada] || 'Rodada ' + ultimaRodada);
    }
}

function formatarFala(texto) {
    texto = texto.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    return texto;
}

function renderResumo(data) {
    var d = data.deliberacao;
    var falas = data.falas || [];
    var execs = data.executivos || {};
    var html = '';

    // Contagem de falas por executivo
    var count = {};
    falas.forEach(function (f) {
        if (!count[f.executivo_sigla]) count[f.executivo_sigla] = 0;
        count[f.executivo_sigla]++;
    });

    var ord = ['CFO', 'COO', 'CMO', 'CHRO', 'CEO'];
    ord.forEach(function (s) {
        var e = execs[s] || {};
        html += '<div class="resumo-item">' +
            '<div class="resumo-rodada"><span class="resumo-sigla" style="color:' + (e.cor || '#fff') + '">' + s + '</span> — ' + (e.nome || '') + '</div>' +
            '<div class="resumo-fala">' + (count[s] || 0) + ' falas proferidas</div>' +
            '</div>';
    });

    if (d && d.decisao_final) {
        html += '<div class="decisao-box">' +
            '<div class="label"><i class="fa fa-gavel"></i> Decisão Final</div>' +
            '<div class="texto">' + formatarFala(d.decisao_final) + '</div>' +
            '</div>';
    }

    $('#resumoArea').html(html);
}

function renderHistorico(historico) {
    if (!historico || historico.length === 0) {
        $('#historicoLista').html('<div style="color:var(--muted);text-align:center;padding:20px">Nenhuma deliberação anterior.</div>');
        return;
    }
    var html = '';
    historico.forEach(function (h) {
        var badge = h.status === 'concluido' ? 'concluido' : 'em_andamento';
        html += '<div class="historico-item" onclick="verHistorico(' + h.id + ')">' +
            '<div class="historico-pauta">' + h.pauta + '</div>' +
            '<div class="historico-meta"><span class="historico-badge ' + badge + '">' + h.status + '</span> ' + formatarData(h.created_at) + '</div>' +
            '</div>';
    });
    $('#historicoLista').html(html);
}

function verHistorico(id) {
    boardId = id;
    $('#transcricao').empty();
    $('#resumoArea').html('<div class="resumo-vazio"><i class="fa fa-spinner fa-spin"></i> Carregando...</div>');
    $('#painelPauta').slideUp();
    $('#painelBoard').slideDown();
    $('#btnExportarCSV').show();
    renderExecutivos();
    carregarStatus();
    if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
}

function formatarData(dt) {
    if (!dt) return '';
    var d = new Date(dt.replace(' ', 'T'));
    return d.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}
