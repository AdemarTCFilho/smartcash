var tabAtiva = 'pagar';

function atualizarPeriodoSelect() {
    var tipo = $('#tipoPeriodoSelect').val();
    var sel = $('#periodoSelect');
    sel.empty();
    var now = new Date();
    if (tipo === 'mensal') {
        for (var i = 0; i < 12; i++) {
            var d = new Date(now.getFullYear(), now.getMonth() - i, 1);
            var v = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0');
            var l = d.toLocaleString('pt-BR', { month:'long', year:'numeric' });
            sel.append('<option value="'+v+'">'+l+'</option>');
        }
    } else if (tipo === 'trimestral') {
        var trim = Math.floor(now.getMonth() / 3) + 1;
        for (var i = 0; i < 8; i++) {
            var t = trim - i;
            var ano = now.getFullYear();
            while (t < 1) { t += 4; ano--; }
            var v = 'T' + t + '/' + ano;
            var l = t + '\u00ba Trimestre ' + ano;
            sel.append('<option value="'+v+'">'+l+'</option>');
        }
    } else if (tipo === 'anual') {
        for (var i = 0; i < 5; i++) {
            var v = now.getFullYear() - i;
            sel.append('<option value="'+v+'">'+v+'</option>');
        }
    }
}

function carregarDados() {
    var params = {
        tipoPeriodo: $('#tipoPeriodoSelect').val(),
        periodo: $('#periodoSelect').val(),
        idEmpresa: $('#empresaSelect').val(),
        busca: $('#buscaInput').val(),
    };
    $.ajax({
        url: base_url + 'BaixasFinanceiras/getDados',
        type: 'GET',
        data: params,
        dataType: 'json',
        success: function(r) {
            atualizarCards(r.resumo);
            atualizarTabelaPagar(r.aPagar);
            atualizarTabelaReceber(r.aReceber);
            atualizarTabelaHistPag(r.histPag);
            atualizarTabelaHistRec(r.histRec);
        }
    });
}

function atualizarCards(r) {
    $('#saldoDevedor').text('R$ ' + formatReal(r.saldoDevedor));
    $('#totalAPagar').text(r.totalAPagar);
    $('#saldoReceber').text('R$ ' + formatReal(r.saldoReceber));
    $('#totalAReceber').text(r.totalAReceber);
}

function formatReal(v) {
    v = parseFloat(v) || 0;
    return v.toFixed(2).replace('.', ',');
}

function formatDate(d) {
    if (!d) return '-';
    var p = d.split('-');
    if (p.length !== 3) return d;
    return p[2] + '/' + p[1] + '/' + p[0];
}

function atualizarTabelaPagar(dados) {
    var tbody = $('#tabelaPagar tbody');
    tbody.empty();
    if (!dados || dados.length === 0) {
        tbody.append('<tr><td colspan="7" class="vazio">Nenhum t\u00edtulo pendente.</td></tr>');
        return;
    }
    $.each(dados, function(i, item) {
        var valor = parseFloat(item.valor) || 0;
        var valorPago = parseFloat(item.valorPago) || 0;
        var saldo = valor - valorPago;
        var statusClass = item.status === 'liquidado' ? 'liquidado' : 'pendente';
        var tr = '<tr>' +
            '<td>' + (item.fornecedor || '-') + '</td>' +
            '<td>' + formatDate(item.vencimento) + '</td>' +
            '<td>R$ ' + formatReal(valor) + '</td>' +
            '<td>R$ ' + formatReal(valorPago) + '</td>' +
            '<td>R$ ' + formatReal(saldo) + '</td>' +
            '<td><span class="badge ' + statusClass + '">' + item.status + '</span></td>' +
            '<td><button class="baixar-btn" data-id="' + item.idContaPagar + '" data-tipo="pagar" data-valor="' + valor + '">Baixar</button></td>' +
            '</tr>';
        tbody.append(tr);
    });
}

function atualizarTabelaReceber(dados) {
    var tbody = $('#tabelaReceber tbody');
    tbody.empty();
    if (!dados || dados.length === 0) {
        tbody.append('<tr><td colspan="7" class="vazio">Nenhum t\u00edtulo pendente.</td></tr>');
        return;
    }
    $.each(dados, function(i, item) {
        var valor = parseFloat(item.valor) || 0;
        var valorRecebido = parseFloat(item.valorRecebido) || 0;
        var saldo = valor - valorRecebido;
        var statusClass = item.status === 'liquidado' ? 'liquidado' : 'pendente';
        var tr = '<tr>' +
            '<td>' + (item.nomeCliente || '-') + '</td>' +
            '<td>' + formatDate(item.vencimento) + '</td>' +
            '<td>R$ ' + formatReal(valor) + '</td>' +
            '<td>R$ ' + formatReal(valorRecebido) + '</td>' +
            '<td>R$ ' + formatReal(saldo) + '</td>' +
            '<td><span class="badge ' + statusClass + '">' + item.status + '</span></td>' +
            '<td><button class="baixar-btn" data-id="' + item.idContaReceber + '" data-tipo="receber" data-valor="' + valor + '">Baixar</button></td>' +
            '</tr>';
        tbody.append(tr);
    });
}

function atualizarTabelaHistPag(dados) {
    var tbody = $('#tabelaHistPag tbody');
    tbody.empty();
    if (!dados || dados.length === 0) {
        tbody.append('<tr><td colspan="5" class="vazio">Nenhum pagamento registrado.</td></tr>');
        return;
    }
    $.each(dados, function(i, item) {
        var tr = '<tr>' +
            '<td>' + (item.fornecedor || '-') + '</td>' +
            '<td>' + formatDate(item.vencimento) + '</td>' +
            '<td>R$ ' + formatReal(item.valor) + '</td>' +
            '<td>R$ ' + formatReal(item.valorPago) + '</td>' +
            '<td>' + formatDate(item.dataPagamento) + '</td>' +
            '</tr>';
        tbody.append(tr);
    });
}

function atualizarTabelaHistRec(dados) {
    var tbody = $('#tabelaHistRec tbody');
    tbody.empty();
    if (!dados || dados.length === 0) {
        tbody.append('<tr><td colspan="5" class="vazio">Nenhum recebimento registrado.</td></tr>');
        return;
    }
    $.each(dados, function(i, item) {
        var tr = '<tr>' +
            '<td>' + (item.nomeCliente || '-') + '</td>' +
            '<td>' + formatDate(item.vencimento) + '</td>' +
            '<td>R$ ' + formatReal(item.valor) + '</td>' +
            '<td>R$ ' + formatReal(item.valorRecebido) + '</td>' +
            '<td>' + formatDate(item.dataRecebimento) + '</td>' +
            '</tr>';
        tbody.append(tr);
    });
}

function carregarEmpresas() {
    $.ajax({
        url: base_url + 'BaixasFinanceiras/listarEmpresas',
        type: 'GET',
        dataType: 'json',
        success: function(r) {
            var sel = $('#empresaSelect');
            r.data.forEach(function(e) {
                sel.append('<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>');
            });
            sel.trigger('change');
        }
    });
}

$(document).ready(function() {
    atualizarPeriodoSelect();
    carregarEmpresas();

    $('#empresaSelect').select2({
        width: '200px',
        allowClear: true,
        placeholder: 'Todas as Empresas'
    });

    $('#tipoPeriodoSelect').on('change', function() {
        atualizarPeriodoSelect();
        carregarDados();
    });

    $('#periodoSelect').on('change', carregarDados);
    $('#empresaSelect').on('change', carregarDados);
    $('#buscaInput').on('keyup', function() {
        clearTimeout($(this).data('timer'));
        $(this).data('timer', setTimeout(carregarDados, 500));
    });

    $('[data-tab]').on('click', function() {
        var tab = $(this).data('tab');
        tabAtiva = tab;
        $('[data-tab]').removeClass('active');
        $(this).addClass('active');
        $('.tab-content').removeClass('active');
        $('#tab-' + tab).addClass('active');
    });

    $(document).on('click', '.baixar-btn', function() {
        var id = $(this).data('id');
        var tipo = $(this).data('tipo');
        var valor = $(this).data('valor');
        $('#baixarId').val(id);
        $('#baixarTipo').val(tipo);
        $('#baixarValorOriginal').text(formatReal(valor));
        $('#baixarValor').val(formatReal(valor));
        var title = tipo === 'pagar' ? 'Baixar Conta a Pagar' : 'Baixar Conta a Receber';
        $('#baixarModalTitle').text(title);
        $('#baixarModal').modal('show');
    });

    $('#confirmarBaixa').on('click', function() {
        var id = $('#baixarId').val();
        var tipo = $('#baixarTipo').val();
        var valor = $('#baixarValor').val();
        if (!id || !valor) { alert('Preencha todos os campos.'); return; }
        var url = tipo === 'pagar' ? 'BaixasFinanceiras/baixarPagar' : 'BaixasFinanceiras/baixarReceber';
        $.ajax({
            url: base_url + url,
            type: 'POST',
            data: { id: id, valorPago: valor, valorRecebido: valor },
            dataType: 'json',
            success: function(r) {
                if (r.success) {
                    $('#baixarModal').modal('hide');
                    carregarDados();
                }
                Swal.fire(r.success ? 'Sucesso' : 'Erro', r.message, r.success ? 'success' : 'error');
            }
        });
    });

    $('#exportCSV').on('click', function() {
        var params = {
            tipoPeriodo: $('#tipoPeriodoSelect').val(),
            periodo: $('#periodoSelect').val(),
            idEmpresa: $('#empresaSelect').val(),
            aba: tabAtiva
        };
        var qs = Object.keys(params).map(function(k) {
            return k + '=' + encodeURIComponent(params[k] || '');
        }).join('&');
        window.open(base_url + 'BaixasFinanceiras/exportarCSV?' + qs, '_blank');
    });

    carregarDados();
});
