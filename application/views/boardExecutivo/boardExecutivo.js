var boardId = null;
var pollTimer = null;
var executivos = {};
var metricas = {};

$(document).ready(function () {
  carregarDadosIniciais();

  $('#btnConvocar').on('click', function () {
    var pauta = $('#inputPauta').val().trim();
    if (!pauta) { alert('Informe a pauta para convocar o board.'); return; }
    var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Convocando...');
    $.ajax({
      url: siteUrl + 'boardExecutivo/convocar',
      type: 'POST',
      data: { pauta: pauta },
      dataType: 'json',
      success: function (r) {
        if (r.erro) { alert(r.erro); $btn.prop('disabled', false).html('<i class="fa fa-bullhorn"></i> Convocar Board'); return; }
        boardId = r.id;
        iniciarBoard();
      },
      error: function () {
        alert('Erro ao convocar board.');
        $btn.prop('disabled', false).html('<i class="fa fa-bullhorn"></i> Convocar Board');
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
      renderCards();
      renderHistorico(r.historico || []);
      $('#totalExecutivos').text(Object.keys(executivos).length);
    }
  });
}

function iniciarBoard() {
  $('#painelBoardBar').slideDown();
  $('#painelTranscricao').slideDown();
  $('#btnExportarCSV').show();
  $('#transcricao').html('<div class="transcricao-empty"><i class="fa fa-spinner fa-spin" style="font-size:32px;display:block;margin-bottom:12px"></i> Carregando deliberação...</div>');
  $('#decisaoFinalArea').hide().empty();

  renderCards();
  $('#rodadaLabel').text('Parecer Inicial');
  carregarStatus();
  if (pollTimer) clearInterval(pollTimer);
  pollTimer = setInterval(carregarStatus, 3000);
}

function renderCards() {
  var ord = ['HV', 'BT', 'PL', 'DR', 'RM'];
  var html = '';
  ord.forEach(function (s) {
    var e = executivos[s];
    if (!e) return;
    html += '<div class="exec-card" id="card-' + s + '">' +
      '<div class="exec-avatar" style="background:' + e.cor + '">' + s + '</div>' +
      '<div class="exec-nome">' + e.nome + '</div>' +
      '<div class="exec-cargo">' + e.cargo + '</div>' +
      '<div class="exec-falas"><i class="fa fa-comment"></i> <b id="falas-' + s + '">0</b> falas</div>' +
      '</div>';
  });
  $('#execCards').html(html);
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
      var d = r.deliberacao;
      if (d) {
        if (d.status === 'concluido') {
          $('#statusLabel').text('Concluído').css('color', '#22c55e');
          if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
          $('#btnConvocar').prop('disabled', false).html('<i class="fa fa-bullhorn"></i> Convocar Board');
          if (d.decisao_final) {
            $('#decisaoFinalArea').html(
              '<div class="decisao-label"><i class="fa fa-gavel"></i> Decisão Final do CEO</div>' +
              '<div class="decisao-texto">' + formatarFala(d.decisao_final) + '</div>'
            ).slideDown();
          }
        }
      }
    }
  });
}

function renderFalas(falas, execs) {
  var nomesRodada = ['', 'Parecer Inicial', 'Réplica', 'Decisão Final'];
  var container = $('#transcricao');

  if (container.children('.fala-item, .rodada-divider').length === 0) {
    container.empty();
  }

  falas.forEach(function (f, idx) {
    var e = execs[f.executivo_sigla] || {};
    var cor = e.cor || '#a855f7';
    var nome = e.nome || f.executivo_sigla;
    var cargo = e.cargo || '';
    var rodNome = nomesRodada[f.rodada] || '';

    var dk = 'div-' + f.rodada;
    if ($('#' + dk).length === 0) {
      container.append('<div class="rodada-divider" id="' + dk + '">' + rodNome + '</div>');
    }

    var fk = 'fala-' + f.id;
    if ($('#' + fk).length > 0) return;

    var ts = f.created_at
      ? new Date(f.created_at.replace(' ', 'T')).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
      : '';

    var html = '<div class="fala-item" id="' + fk + '">' +
      '<div class="fala-avatar" style="background:' + cor + '">' + f.executivo_sigla + '</div>' +
      '<div class="fala-body">' +
      '<div class="fala-header"><span class="fala-nome" style="color:' + cor + '">' + nome + '</span><span class="fala-cargo">' + cargo + '</span></div>' +
      '<div class="fala-texto">' + formatarFala(f.fala) + '</div>' +
      '<div class="fala-tempo">' + ts + '</div>' +
      '</div></div>';
    container.append(html);

    // Update counter
    var $fc = $('#falas-' + f.executivo_sigla);
    if ($fc.length) $fc.text(parseInt($fc.text()) + 1);

    // Pulse card
    $('.exec-card').removeClass('ativo pulando');
    $('#card-' + f.executivo_sigla).addClass('ativo pulando');
    setTimeout(function () { $('#card-' + f.executivo_sigla).removeClass('pulando'); }, 500);

    container.scrollTop(container[0].scrollHeight);
  });

  if (falas.length > 0) {
    var ultima = falas[falas.length - 1].rodada;
    $('#rodadaLabel').text(nomesRodada[ultima] || 'Rodada ' + ultima);
  }
}

function formatarFala(t) {
  return t.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
}

function renderHistorico(h) {
  if (!h || h.length === 0) {
    $('#historicoLista').html('<div class="empty-state">Nenhuma deliberação anterior.</div>');
    return;
  }
  var html = '';
  h.forEach(function (item) {
    var badge = item.status === 'concluido' ? 'concluido' : 'em_andamento';
    html += '<div class="historico-item" onclick="verHistorico(' + item.id + ')">' +
      '<div class="historico-pauta">' + item.pauta + '</div>' +
      '<div class="historico-meta"><span class="historico-badge ' + badge + '">' + item.status + '</span> ' + fmtData(item.created_at) + '</div>' +
      '</div>';
  });
  $('#historicoLista').html(html);
}

function verHistorico(id) {
  boardId = id;
  $('#transcricao').html('<div class="transcricao-empty"><i class="fa fa-spinner fa-spin" style="font-size:32px;display:block;margin-bottom:12px"></i> Carregando...</div>');
  $('#decisaoFinalArea').hide().empty();
  $('#painelBoardBar').slideDown();
  $('#painelTranscricao').slideDown();
  $('#btnExportarCSV').show();
  renderCards();
  carregarStatus();
  if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
}

function fmtData(dt) {
  if (!dt) return '';
  var d = new Date(dt.replace(' ', 'T'));
  return d.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}
