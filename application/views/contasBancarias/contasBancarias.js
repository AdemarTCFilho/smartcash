function mascaraMonetaria(el) {
    var v = el.value.replace(/\D/g, '');
    v = (parseInt(v || 0) / 100).toFixed(2) + '';
    v = v.replace('.', ',');
    v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    el.value = v;
}

function formatReal(v) {
    v = parseFloat(v) || 0;
    return v.toFixed(2).replace('.', ',');
}

function formatCoin(v) {
    return 'R$ ' + formatReal(v);
}

function carregarContas() {
    $.ajax({
        url: base_url + 'ContasBancarias/getDados',
        type: 'GET',
        dataType: 'json',
        success: function(r) {
            $('#saldoConsolidado').text(formatCoin(r.saldoConsolidado));
            var tbody = $('#tbodyContas');
            tbody.empty();
            if (!r.contas || r.contas.length === 0) {
                tbody.append('<tr><td colspan="9" class="vazio">Nenhuma conta cadastrada.</td></tr>');
                return;
            }
            $.each(r.contas, function(i, c) {
                var saldo = parseFloat(c.saldoInicial) || 0;
                var tr = '<tr>' +
                    '<td>' + (c.nome || '-') + '</td>' +
                    '<td>' + (c.banco || '-') + '</td>' +
                    '<td>' + (c.agencia || '-') + '</td>' +
                    '<td>' + (c.conta || '-') + '</td>' +
                    '<td>' + (c.tipo || '-') + '</td>' +
                    '<td>R$ 0,00</td>' +
                    '<td>R$ 0,00</td>' +
                    '<td>' + formatCoin(saldo) + '</td>' +
                    '<td>' +
                        '<button class="action-btn edit editarConta" data-id="' + c.idContaBancaria + '">Editar</button>' +
                        '<button class="action-btn delete excluirConta" data-id="' + c.idContaBancaria + '">Excluir</button>' +
                    '</td>' +
                    '</tr>';
                tbody.append(tr);
            });
        }
    });
}

function refreshSelect2(sel) {
    sel.trigger('change.select2');
}

function carregarSelects() {
    $.ajax({
        url: base_url + 'ContasBancarias/getEmpresas',
        type: 'GET',
        dataType: 'json',
        success: function(r) {
            var sel = $('#idEmpresa');
            sel.find('option:not(:first)').remove();
            if (r && r.length) {
                $.each(r, function(i, item) {
                    sel.append('<option value="' + item.idEmpresa + '">' + item.nomeEmpresa + '</option>');
                });
            }
            refreshSelect2(sel);
        },
        error: function() { refreshSelect2($('#idEmpresa')); }
    });

    $.ajax({
        url: base_url + 'ContasBancarias/getUsuarios',
        type: 'GET',
        dataType: 'json',
        success: function(r) {
            var sel = $('#idUsuarios');
            sel.find('option:not(:first)').remove();
            if (r && r.length) {
                $.each(r, function(i, item) {
                    sel.append('<option value="' + item.idUsuarios + '">' + item.nome + '</option>');
                });
            }
            refreshSelect2(sel);
        },
        error: function() { refreshSelect2($('#idUsuarios')); }
    });

    $.ajax({
        url: base_url + 'ContasBancarias/getUnidades',
        type: 'GET',
        dataType: 'json',
        success: function(r) {
            var sel = $('#idUnidade');
            sel.find('option:not(:first)').remove();
            if (r && r.length) {
                $.each(r, function(i, item) {
                    sel.append('<option value="' + item.idUnidade + '">' + item.nomeUnidade + '</option>');
                });
            }
            refreshSelect2(sel);
        },
        error: function() { refreshSelect2($('#idUnidade')); }
    });
}

function carregarSubUnidades(idUnidade, selectedVal) {
    $.ajax({
        url: base_url + 'ContasBancarias/getSubUnidades',
        type: 'GET',
        data: { idUnidade: idUnidade || '' },
        dataType: 'json',
        success: function(r) {
            var sel = $('#idSubUnidade');
            sel.find('option:not(:first)').remove();
            if (r && r.length) {
                $.each(r, function(i, item) {
                    sel.append('<option value="' + item.idSubUnidade + '">' + item.nomeSubUnidade + '</option>');
                });
            }
            if (selectedVal) { sel.val(selectedVal); }
            refreshSelect2(sel);
        }
    });
}

function resetSelect2(selId) {
    var sel = $(selId);
    sel.val('').trigger('change');
}

function abrirModal(title, dados) {
    $('#contaModalTitle').text(title);
    if (dados) {
        $('#idContaBancaria').val(dados.idContaBancaria || '');
        $('#nome').val(dados.nome || '');
        $('#banco').val(dados.banco || '');
        $('#agencia').val(dados.agencia || '');
        $('#conta').val(dados.conta || '');
        $('#tipo').val(dados.tipo || '');
        $('#saldoInicial').val(formatReal(dados.saldoInicial));
        $('#idEmpresa').val(dados.idEmpresa || '').trigger('change');
        $('#idUsuarios').val(dados.idUsuarios || '').trigger('change');
        $('#idUnidade').val(dados.idUnidade || '').trigger('change');
        $('#observacoes').val(dados.observacoes || '');
        if (dados.idUnidade) {
            carregarSubUnidades(dados.idUnidade, dados.idSubUnidade || '');
        } else {
            var sel = $('#idSubUnidade');
            sel.find('option:not(:first)').remove();
            refreshSelect2(sel);
        }
    } else {
        $('#idContaBancaria').val('');
        $('#nome').val('');
        $('#banco').val('');
        $('#agencia').val('');
        $('#conta').val('');
        $('#tipo').val('');
        $('#saldoInicial').val('');
        $('#observacoes').val('');
        resetSelect2('#idEmpresa');
        resetSelect2('#idUsuarios');
        resetSelect2('#idUnidade');
        var selSub = $('#idSubUnidade');
        selSub.find('option:not(:first)').remove();
        refreshSelect2(selSub);
    }
    $('#contaModal').modal('show');
}

$(document).ready(function() {
    $('.select2-modal').select2({
        width: '100%',
        dropdownParent: $('#contaModal'),
        minimumResultsForSearch: 5,
    });

    carregarSelects();
    carregarContas();

    $('#idUnidade').on('change', function() {
        carregarSubUnidades($(this).val());
    });

    $('#btnNovaConta').on('click', function() {
        abrirModal('Nova Conta Banc\u00e1ria', null);
    });

    $(document).on('click', '.editarConta', function() {
        var id = $(this).data('id');
        $.ajax({
            url: base_url + 'ContasBancarias/getConta',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(r) {
                if (r) abrirModal('Editar Conta Banc\u00e1ria', r);
            }
        });
    });

    $(document).on('click', '.excluirConta', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Confirmar exclus\u00e3o',
            text: 'Deseja realmente excluir esta conta banc\u00e1ria?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff5b67',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: base_url + 'ContasBancarias/excluir',
                    type: 'POST',
                    data: { idContaBancaria: id },
                    dataType: 'json',
                    success: function(r) {
                        Swal.fire(r.success ? 'Sucesso' : 'Erro', r.message, r.success ? 'success' : 'error');
                        if (r.success) carregarContas();
                    }
                });
            }
        });
    });

    $('#salvarConta').on('click', function() {
        var id = $('#idContaBancaria').val();
        var dados = {
            nome: $('#nome').val(),
            idUsuarios: $('#idUsuarios').val(),
            idEmpresa: $('#idEmpresa').val(),
            idUnidade: $('#idUnidade').val(),
            idSubUnidade: $('#idSubUnidade').val(),
            banco: $('#banco').val(),
            agencia: $('#agencia').val(),
            conta: $('#conta').val(),
            tipo: $('#tipo').val(),
            saldoInicial: $('#saldoInicial').val(),
            observacoes: $('#observacoes').val(),
        };

        if (!dados.nome || !dados.idEmpresa || !dados.idUsuarios || !dados.idUnidade || !dados.banco || !dados.agencia || !dados.conta || !dados.tipo || !dados.saldoInicial) {
            Swal.fire('Aten\u00e7\u00e3o', 'Preencha todos os campos obrigat\u00f3rios.', 'warning');
            return;
        }

        if (id) dados.idContaBancaria = id;

        var url = id ? base_url + 'ContasBancarias/editar' : base_url + 'ContasBancarias/adicionar';

        $.ajax({
            url: url,
            type: 'POST',
            data: dados,
            dataType: 'json',
            success: function(r) {
                Swal.fire(r.success ? 'Sucesso' : 'Erro', r.message, r.success ? 'success' : 'error');
                if (r.success) {
                    $('#contaModal').modal('hide');
                    carregarContas();
                }
            }
        });
    });
});
