document.addEventListener('DOMContentLoaded', function () {
    carregarEmpresas();
    carregarCategoriasFiltro();
    carregarUnidadesFiltro();
    carregarSubUnidadesFiltro();
    carregarUsuariosFiltro();
    carregarContasReceber();
    carregarDashboard();

    document.getElementById('btnNovaConta').addEventListener('click', function () {
        abrirModal();
    });

    document.getElementById('btnImportarCSV').addEventListener('click', function () {
        document.getElementById('csvFileInput').click();
    });

    document.getElementById('csvFileInput').addEventListener('change', function (e) {
        importarCSV(e.target.files[0]);
    });
});

function inicializarSelect2Filtro(sel, placeholder) {
    if (typeof $ !== 'undefined' && $.fn.select2) {
        if ($(sel).data('select2')) $(sel).select2('destroy');
        $(sel).select2({ width: '100%', placeholder: placeholder, allowClear: true });
    }
}

function carregarCategoriasFiltro() {
    fetch(siteUrl + 'contasReceber/listarCategorias')
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Todas as categorias</option>';
            res.data.forEach(c => {
                opts += '<option value="' + c.idCategoria + '">' + c.nomeCategoria + '</option>';
            });
            let sel = document.getElementById('filtroCategoria');
            sel.innerHTML = opts;
            inicializarSelect2Filtro(sel, 'Todas as categorias');
        });
}

function carregarUnidadesFiltro() {
    fetch(siteUrl + 'contasReceber/listarTodasUnidades')
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Todas as unidades</option>';
            res.data.forEach(u => {
                opts += '<option value="' + u.idUnidade + '">' + u.nomeUnidade + '</option>';
            });
            let sel = document.getElementById('filtroUnidade');
            sel.innerHTML = opts;
            inicializarSelect2Filtro(sel, 'Todas as unidades');
        });
}

function carregarSubUnidadesFiltro() {
    fetch(siteUrl + 'contasReceber/listarTodasSubUnidades')
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Todos os departamentos</option>';
            res.data.forEach(s => {
                opts += '<option value="' + s.idSubUnidade + '">' + s.nomeSubUnidade + '</option>';
            });
            let sel = document.getElementById('filtroSubUnidade');
            sel.innerHTML = opts;
            inicializarSelect2Filtro(sel, 'Todos os departamentos');
        });
}

function carregarUsuariosFiltro() {
    fetch(siteUrl + 'contasReceber/listarUsuarios')
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Todos os usuários</option>';
            res.data.forEach(u => {
                opts += '<option value="' + u.idUsuarios + '">' + u.nome + '</option>';
            });
            let sel = document.getElementById('filtroUsuario');
            sel.innerHTML = opts;
            inicializarSelect2Filtro(sel, 'Todos os usuários');
        });
}

function toggleFiltroAvancado() {
    let painel = document.getElementById('filtroAvancado');
    let btn = document.getElementById('btnFiltroAvancado');
    let aberto = painel.style.display === 'flex';
    painel.style.display = aberto ? 'none' : 'flex';
    btn.classList.toggle('ativo', !aberto);
}

function aplicarAtalhoPeriodo(tipo) {
    let hoje = new Date();
    let de = document.getElementById('filtroDataDe');
    let ate = document.getElementById('filtroDataAte');

    if (tipo === 'hoje') {
        let iso = dateToInputValue(hoje);
        de.value = iso;
        ate.value = iso;
    } else if (tipo === 'mes') {
        let primeiro = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
        let ultimo = new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0);
        de.value = dateToInputValue(primeiro);
        ate.value = dateToInputValue(ultimo);
    }

    pesquisarContasReceber();
}

function pesquisarContasReceber() {
    carregarContasReceber();
}

function formatMoney(val) {
    if (!val && val !== 0) return 'R$ 0,00';
    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    let parts = dateStr.split('-');
    if (parts.length !== 3) return dateStr;
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

function formatCompetencia(competencia) {
    if (!competencia) return '-';
    let parts = competencia.split('-');
    if (parts.length !== 2) return competencia;
    return parts[1] + '/' + parts[0];
}

function formatDateHora(dateTimeStr) {
    if (!dateTimeStr) return '-';
    let [dataParte, horaParte] = dateTimeStr.split(' ');
    return formatDate(dataParte) + (horaParte ? ' às ' + horaParte.substring(0, 5) : '');
}

function mascaraMonetaria(el) {
    let v = el.value.replace(/\D/g, '');
    if (!v) { el.value = ''; return; }
    v = (parseInt(v) / 100).toFixed(2);
    el.value = v.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseMoneyInput(val) {
    if (!val) return 0;
    return parseFloat(String(val).replace(/\./g, '').replace(',', '.')) || 0;
}

function dateToInputValue(dt) {
    return dt.getFullYear() + '-' + String(dt.getMonth() + 1).padStart(2, '0') + '-' + String(dt.getDate()).padStart(2, '0');
}

function toggleRepetir() {
    let repetirBox = document.getElementById('swal-repetir');
    document.getElementById('swal-bloco-repetir').style.display = repetirBox.checked ? 'block' : 'none';
}

function toggleQtdRepeticoes() {
    let tipo = document.getElementById('swal-tipo-repeticao').value;
    document.getElementById('swal-qtd-repeticoes-wrap').style.display = tipo === 'especifica' ? 'block' : 'none';
}

function carregarEmpresas() {
    fetch(siteUrl + 'contasReceber/listarEmpresas')
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Todas as empresas</option>';
            res.data.forEach(e => {
                opts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
            });
            let sel = document.getElementById('filtroEmpresa');
            sel.innerHTML = opts;
            inicializarSelect2Filtro(sel, 'Todas as empresas');
        });
}

function carregarUnidades(empresaId, selectId) {
    let select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Carregando...</option>';
    if (!empresaId) {
        select.innerHTML = '<option value="">Selecione uma unidade</option>';
        return Promise.resolve();
    }
    return fetch(siteUrl + 'contasReceber/listarUnidadesPorEmpresa?idEmpresa=' + empresaId)
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Selecione uma unidade</option>';
            res.data.forEach(u => {
                opts += '<option value="' + u.idUnidade + '">' + u.nomeUnidade + '</option>';
            });
            select.innerHTML = opts;
        });
}

function carregarSubUnidades(unidadeId, selectId) {
    let select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Carregando...</option>';
    if (!unidadeId) {
        select.innerHTML = '<option value="">Selecione um departamento</option>';
        return Promise.resolve();
    }
    return fetch(siteUrl + 'contasReceber/listarSubUnidadesPorUnidade?idUnidade=' + unidadeId)
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Selecione um departamento</option>';
            res.data.forEach(s => {
                opts += '<option value="' + s.idSubUnidade + '">' + s.nomeSubUnidade + '</option>';
            });
            select.innerHTML = opts;
        });
}

function carregarSubCategoriasPorCategoria(idCategoria, selectId) {
    let select = document.getElementById(selectId);
    if (!idCategoria) {
        select.innerHTML = '<option value="">Selecione uma classificação</option>';
        return Promise.resolve();
    }
    select.innerHTML = '<option value="">Carregando...</option>';
    return fetch(siteUrl + 'contasReceber/listarSubCategoriasPorCategoria?idCategoria=' + idCategoria)
        .then(res => res.json())
        .then(res => {
            let opts = '<option value="">Selecione uma classificação</option>';
            res.data.forEach(s => {
                opts += '<option value="' + s.idSubCategoria + '">' + s.nomeSubCategoria + '</option>';
            });
            select.innerHTML = opts;
        });
}

function carregarDashboard() {
    fetch(siteUrl + 'contasReceber/getDadosDashboard')
        .then(res => res.json())
        .then(res => {
            document.getElementById('totalAReceber').textContent = formatMoney(res.totalAReceber.total);
            document.getElementById('totalAReceberContas').textContent = res.totalAReceber.totalContas + ' conta(s)';

            document.getElementById('totalRecebido').textContent = formatMoney(res.totalRecebido.total);
            document.getElementById('totalRecebidoContas').textContent = res.totalRecebido.totalContas + ' conta(s)';

            if (res.proximoVencimento) {
                document.getElementById('proximoVencimento').textContent = formatDate(res.proximoVencimento.vencimento);
                document.getElementById('proximoVencimentoCliente').textContent = res.proximoVencimento.nomeCliente || '';
            } else {
                document.getElementById('proximoVencimento').textContent = '-';
                document.getElementById('proximoVencimentoCliente').textContent = '';
            }

            let catHtml = '';
            if (res.receitasPorCategoria && res.receitasPorCategoria.length > 0) {
                res.receitasPorCategoria.forEach(c => {
                    catHtml += '<div class="item-categoria"><span>' + c.nomeCategoria + '</span><span>' + formatMoney(c.total) + '</span></div>';
                });
            } else {
                catHtml = '<div class="muted">Sem lançamentos ainda.</div>';
            }
            document.getElementById('receitasCategoriaContent').innerHTML = catHtml;

            let venHtml = '';
            if (res.proximosVencimentos && res.proximosVencimentos.length > 0) {
                res.proximosVencimentos.forEach(v => {
                    venHtml += '<div class="item-vencimento"><span class="vencimento-label">' + formatDate(v.vencimento) + '</span><span class="vencimento-valor">' + (v.nomeCliente || '') + ' — ' + formatMoney(v.valor) + '</span></div>';
                });
            } else {
                venHtml = '<div class="muted">Nenhuma conta em aberto.</div>';
            }
            document.getElementById('proximosVencimentosContent').innerHTML = venHtml;
        });
}

function carregarContasReceber() {
    let busca = document.getElementById('filtroBusca').value.trim().toLowerCase();
    let dataDe = document.getElementById('filtroDataDe').value;
    let dataAte = document.getElementById('filtroDataAte').value;
    let empresa = document.getElementById('filtroEmpresa').value;
    let categoria = document.getElementById('filtroCategoria').value;
    let unidade = document.getElementById('filtroUnidade').value;
    let subunidade = document.getElementById('filtroSubUnidade').value;
    let usuario = document.getElementById('filtroUsuario').value;

    fetch(siteUrl + 'contasReceber/listar')
        .then(res => res.json())
        .then(res => {
            let dados = res.data;

            if (busca) dados = dados.filter(d => (d.nomeCliente || '').toLowerCase().includes(busca));
            if (dataDe) dados = dados.filter(d => d.vencimento && d.vencimento >= dataDe);
            if (dataAte) dados = dados.filter(d => d.vencimento && d.vencimento <= dataAte);
            if (empresa) dados = dados.filter(d => String(d.idEmpresa) === String(empresa));
            if (categoria) dados = dados.filter(d => String(d.idCategoria) === String(categoria));
            if (unidade) dados = dados.filter(d => String(d.idUnidade) === String(unidade));
            if (subunidade) dados = dados.filter(d => String(d.idSubUnidade) === String(subunidade));
            if (usuario) dados = dados.filter(d => String(d.idUsuarios) === String(usuario));

            let html = '';
            let gruposRenderizados = {};
            let totalLinhasVisiveis = 0;

            dados.forEach(d => {
                if (d.grupoLancamento) {
                    if (gruposRenderizados[d.grupoLancamento]) return;
                    gruposRenderizados[d.grupoLancamento] = true;
                    totalLinhasVisiveis++;

                    let itens = dados.filter(x => x.grupoLancamento === d.grupoLancamento);
                    let representante = escolherRepresentanteGrupo(itens);
                    let outros = itens.filter(x => x !== representante);

                    let clienteHtml = '<span class="grupo-toggle" data-grupo="' + d.grupoLancamento + '">▸</span> ' +
                        (representante.nomeCliente || '—') +
                        (outros.length > 0 ? ' <span class="tag-grupo">' + itens.length + 'x</span>' : '');

                    html += montarLinhaContaReceber(representante, {
                        clienteHtml: clienteHtml,
                        onClickRow: outros.length > 0 ? "toggleGrupo('" + d.grupoLancamento + "')" : undefined
                    });

                    outros.forEach(o => {
                        html += montarLinhaContaReceber(o, {
                            clienteHtml: '<span class="grupo-indent">&nbsp;&nbsp;&nbsp;&nbsp;↳</span> ' + (o.nomeCliente || '—'),
                            extraClass: 'linha-grupo-' + d.grupoLancamento + ' linha-subitem',
                            oculta: true
                        });
                    });
                } else {
                    totalLinhasVisiveis++;
                    html += montarLinhaContaReceber(d);
                }
            });

            document.getElementById('tabelaContasReceber').innerHTML = html;
            document.getElementById('totalRegistros').textContent = totalLinhasVisiveis + ' registro(s)' +
                (dados.length !== totalLinhasVisiveis ? ' (' + dados.length + ' lançamentos)' : '');
        });
}

function escolherRepresentanteGrupo(itens) {
    let hoje = new Date();
    let inicioMes = dateToInputValue(new Date(hoje.getFullYear(), hoje.getMonth(), 1));
    let fimMes = dateToInputValue(new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0));

    let doMesAtual = itens.find(d => d.vencimento >= inicioMes && d.vencimento <= fimMes);
    if (doMesAtual) return doMesAtual;

    let pendente = itens.find(d => d.status !== 'liquidado');
    if (pendente) return pendente;

    return itens[itens.length - 1];
}

function montarLinhaContaReceber(d, opcoes) {
    opcoes = opcoes || {};
    let clienteHtml = opcoes.clienteHtml !== undefined ? opcoes.clienteHtml : (d.nomeCliente || '—');
    let tagRecorrente = d.recorrente == 1 ? ' <span class="tag-recorrente">recorrente</span>' : '';
    let classes = 'linha-clicavel' + (opcoes.extraClass ? ' ' + opcoes.extraClass : '');
    let escondida = opcoes.oculta ? ' style="display:none;"' : '';
    let onClickRow = opcoes.onClickRow || ('visualizarConta(' + d.idContaReceber + ')');

    return '<tr class="' + classes + '"' + escondida + ' onclick="' + onClickRow + '">' +
        '<td>' + clienteHtml + tagRecorrente + '</td>' +
        '<td>' + (d.nomeUsuario || '—') + '</td>' +
        '<td>' + (d.nomeEmpresa || '—') + '</td>' +
        '<td>' + (d.nomeUnidade || '—') + '</td>' +
        '<td>' + (d.nomeSubUnidade || '—') + '</td>' +
        '<td>' + formatMoney(d.valor) + '</td>' +
        '<td>' + formatDate(d.vencimento) + '</td>' +
        '<td><span class="seta-receber">↑</span> ' + (d.nomeCategoria || '—') + '</td>' +
        '<td>' + (d.observacoes ? d.observacoes.substring(0, 30) : '') + '</td>' +
        '<td>' +
            "<span class='edit' onclick='event.stopPropagation(); visualizarConta(" + d.idContaReceber + ")' title='Ver detalhes'><i class='fa fa-eye' aria-hidden='true'></i></span>" +
            "<span class='edit' onclick='event.stopPropagation(); editarConta(" + d.idContaReceber + ")' title='Editar'>✎</span>" +
            "<span class='edit' onclick='event.stopPropagation(); excluirConta(" + d.idContaReceber + ")' title='Excluir'><i class='fa fa-trash-o' aria-hidden='true'></i></span>" +
        '</td>' +
    '</tr>';
}

function toggleGrupo(grupoId) {
    let linhas = document.querySelectorAll('.linha-grupo-' + grupoId);
    if (!linhas.length) return;
    let expandido = linhas[0].style.display !== 'none';
    linhas.forEach(l => l.style.display = expandido ? 'none' : 'table-row');

    let seta = document.querySelector('.grupo-toggle[data-grupo="' + grupoId + '"]');
    if (seta) seta.textContent = expandido ? '▸' : '▾';
}

function visualizarConta(id) {
    fetch(siteUrl + 'contasReceber/getDados?id=' + id)
        .then(res => res.json())
        .then(d => {
            let saldoAReceber = (parseFloat(d.valor) || 0) - (parseFloat(d.valorRecebido) || 0);
            let statusHtml = d.status === 'liquidado'
                ? 'RECEBIDO EM ' + formatDate(d.dataRecebimento)
                : 'PENDENTE';

            let linhaRecorrente = '';
            let botaoCancelar = '';
            if (d.recorrente == 1) {
                let tipoLabel = d.tipoRepeticao === 'indeterminado' ? 'Indeterminada (gerada todo mês)' : 'Quantidade específica';
                let statusRecorrencia = d.recorrenteCancelado == 1 ? ' <span class="tag-cancelada">cancelada</span>' : '';

                linhaRecorrente = `
                    <div class="detalhe-row">
                        <div class="detalhe-item">
                            <div class="detalhe-label">Lançamento Recorrente</div>
                            <div class="detalhe-valor">${d.recorrenteIndex || '-'}${d.recorrenteTotal ? ' / ' + d.recorrenteTotal : ''} — ${tipoLabel}${statusRecorrencia}</div>
                        </div>
                    </div>`;

                if (d.tipoRepeticao === 'indeterminado' && d.recorrenteCancelado != 1) {
                    botaoCancelar = `<div style="margin-top:14px;"><button type="button" class="btn-cancelar-recorrencia" onclick="cancelarRecorrencia('${d.grupoLancamento}')">Cancelar recorrência</button></div>`;
                }
            }

            Swal.fire({
                title: `<span class="seta-receber" style="font-size:16px;">↑</span> ${statusHtml}`,
                customClass: { popup: 'modal-detalhe-conta' },
                html: `
                    <div style="text-align:left;">
                        <div class="detalhe-row">
                            <div class="detalhe-item detalhe-destaque">
                                <div class="detalhe-label">Receber de</div>
                                <div class="detalhe-valor-grande">${d.nomeCliente || '—'}</div>
                            </div>
                            <div class="detalhe-item">
                                <div class="detalhe-label">Vencimento</div>
                                <div class="detalhe-valor">${formatDate(d.vencimento)}</div>
                            </div>
                            <div class="detalhe-item">
                                <div class="detalhe-label">Competência</div>
                                <div class="detalhe-valor">${formatCompetencia(d.competencia)}</div>
                            </div>
                            <div class="detalhe-item">
                                <div class="detalhe-label">Valor original R$</div>
                                <div class="detalhe-valor">${formatMoney(d.valor).replace('R$ ', '')}</div>
                            </div>
                            <div class="detalhe-item">
                                <div class="detalhe-label">Saldo a receber R$</div>
                                <div class="detalhe-valor">${formatMoney(saldoAReceber).replace('R$ ', '')}</div>
                            </div>
                        </div>
                        <hr class="detalhe-divisor">
                        <div class="detalhe-row">
                            <div class="detalhe-item">
                                <div class="detalhe-label">Empresa</div>
                                <div class="detalhe-valor">${d.nomeEmpresa || '-'}</div>
                            </div>
                            <div class="detalhe-item">
                                <div class="detalhe-label">Unidade</div>
                                <div class="detalhe-valor">${d.nomeUnidade || '-'}</div>
                            </div>
                            <div class="detalhe-item">
                                <div class="detalhe-label">Departamento</div>
                                <div class="detalhe-valor">${d.nomeSubUnidade || '-'}</div>
                            </div>
                        </div>
                        <div class="detalhe-row">
                            <div class="detalhe-item">
                                <div class="detalhe-label">Categoria</div>
                                <div class="detalhe-valor">${d.nomeCategoria || '-'}</div>
                            </div>
                            <div class="detalhe-item">
                                <div class="detalhe-label">Classificação</div>
                                <div class="detalhe-valor">${d.nomeSubCategoria || '-'}</div>
                            </div>
                        </div>
                        <div class="detalhe-row">
                            <div class="detalhe-item" style="flex-basis:100%;">
                                <div class="detalhe-label">Observação</div>
                                <div class="detalhe-valor">${d.observacoes || '-'}</div>
                            </div>
                        </div>
                        ${linhaRecorrente}
                        ${botaoCancelar}
                        <div class="detalhe-rodape">Criado por ${d.nomeUsuario || '-'} ${formatDateHora(d.dataCriacao)}</div>
                    </div>
                `,
                showCancelButton: false,
                showCloseButton: true,
                confirmButtonText: 'Voltar',
                confirmButtonColor: '#6b63ff',
            });
        });
}

function cancelarRecorrencia(grupoLancamento) {
    Swal.fire({
        title: 'Cancelar recorrência?',
        text: 'Nenhuma nova conta será gerada a partir do próximo mês. As contas já geradas não serão apagadas.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, cancelar',
        cancelButtonText: 'Voltar',
        confirmButtonColor: '#ff5b67',
    }).then(result => {
        if (result.isConfirmed) {
            let data = new URLSearchParams();
            data.append('grupoLancamento', grupoLancamento);
            fetch(siteUrl + 'contasReceber/cancelarRecorrencia', {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('Pronto', res.message, 'success').then(() => {
                        carregarContasReceber();
                    });
                });
        }
    });
}

function abrirModal(id) {
    let editando = !!id;
    let hoje = new Date();
    let competenciaAtual = hoje.getFullYear() + '-' + String(hoje.getMonth() + 1).padStart(2, '0');

    Promise.all([
        fetch(siteUrl + 'contasReceber/listarClientes').then(r => r.json()),
        fetch(siteUrl + 'contasReceber/listarEmpresas').then(r => r.json()),
        fetch(siteUrl + 'contasReceber/listarCategorias').then(r => r.json()),
    ]).then(([clientesRes, empresasRes, categoriasRes]) => {
        let clientesOpts = '<option value="">Selecione um cliente</option>';
        clientesRes.data.forEach(c => {
            clientesOpts += '<option value="' + c.idClientes + '">' + c.nomeCliente + (c.documento ? ' — ' + c.documento : '') + '</option>';
        });

        let empresasOpts = '<option value="">Selecione uma empresa</option>';
        empresasRes.data.forEach(e => {
            empresasOpts += '<option value="' + e.idEmpresa + '">' + e.nomeEmpresa + '</option>';
        });

        let categoriasOpts = '<option value="">Selecione uma categoria</option>';
        categoriasRes.data.forEach(c => {
            categoriasOpts += '<option value="' + c.idCategoria + '">' + c.nomeCategoria + '</option>';
        });

        Swal.fire({
            title: editando ? 'Editar Conta a Receber' : 'Nova Conta a Receber',
            html: `
                <div style="text-align:left;">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-top:15px;">
                        <div>
                            <label class="swal-label">Cliente *</label>
                            <select id="swal-cliente" class="swal-select select2-modal">
                                ${clientesOpts}
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">Empresa *</label>
                            <select id="swal-empresa" class="swal-select select2-modal" onchange="carregarUnidadesModal(this.value)">
                                ${empresasOpts}
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">Unidade *</label>
                            <select id="swal-unidade" class="swal-select select2-modal" onchange="carregarSubUnidadesModal(this.value)">
                                <option value="">Selecione uma unidade</option>
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">Departamento</label>
                            <select id="swal-subunidade" class="swal-select select2-modal">
                                <option value="">Selecione um departamento</option>
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">Categoria</label>
                            <select id="swal-categoria" class="swal-select select2-modal" onchange="carregarSubCategoriaModal(this.value)">
                                ${categoriasOpts}
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">Classificação no Plano de Contas</label>
                            <select id="swal-subcategoria" class="swal-select select2-modal">
                                <option value="">Selecione uma classificação</option>
                            </select>
                        </div>
                        <div>
                            <label class="swal-label">Valor (R$) *</label>
                            <input id="swal-valor" type="text" class="swal-input" placeholder="0,00" oninput="mascaraMonetaria(this)">
                        </div>
                        <div>
                            <label class="swal-label">Vencimento *</label>
                            <input id="swal-vencimento" type="date" class="swal-input">
                        </div>
                        <div>
                            <label class="swal-label">Competência</label>
                            <input id="swal-competencia" type="month" class="swal-input" value="${editando ? '' : competenciaAtual}">
                        </div>
                    </div>
                    ${editando ? '' : `
                    <div class="checkbox-line">
                        <label><input type="checkbox" id="swal-repetir" onchange="toggleRepetir()"> Repetir</label>
                    </div>
                    <div id="swal-bloco-repetir" style="display:none;">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                            <div>
                                <label class="swal-label">Repetir lançamento</label>
                                <select id="swal-tipo-repeticao" class="swal-select" onchange="toggleQtdRepeticoes()">
                                    <option value="indeterminado">Por prazo indeterminado</option>
                                    <option value="especifica">Quantidade específica</option>
                                </select>
                            </div>
                            <div id="swal-qtd-repeticoes-wrap" style="display:none;">
                                <label class="swal-label">Quantidade de parcelas</label>
                                <select id="swal-qtd-repeticoes" class="swal-select">
                                    <option value="">Selecione</option>
                                    ${Array.from({ length: 24 }, (_, i) => '<option value="' + (i + 1) + '">' + (i + 1) + '</option>').join('')}
                                </select>
                            </div>
                        </div>
                    </div>
                    `}
                    <label class="swal-label" style="margin-top:15px;">Observações:</label>
                    <textarea id="swal-obs" class="swal-textarea" placeholder="Opcional"></textarea>
                </div>
            `,
            customClass: { popup: 'modal-conta-receber' },
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Salvar',
            confirmButtonColor: '#6b63ff',
            cancelButtonColor: '#040414',
            focusConfirm: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: function () {
                let popup = Swal.getPopup();
                $(popup).find('.select2-modal').each(function () {
                    let $select = $(this);
                    $select.select2({
                        dropdownParent: $(popup),
                        width: '100%',
                        placeholder: $select.find('option:first').text(),
                        allowClear: true,
                    });
                });

                if (editando) {
                    carregarDadosEdicao(id, popup);
                }
            },
            preConfirm: function () {
                let cliente = $('#swal-cliente').val();
                let empresa = $('#swal-empresa').val();
                let unidade = $('#swal-unidade').val();
                let valor = $('#swal-valor').val();
                let vencimento = $('#swal-vencimento').val();

                if (!cliente) { Swal.showValidationMessage('Selecione um cliente'); return false; }
                if (!empresa) { Swal.showValidationMessage('Selecione uma empresa'); return false; }
                if (!unidade) { Swal.showValidationMessage('Selecione uma unidade'); return false; }
                if (!valor) { Swal.showValidationMessage('Informe o valor'); return false; }
                if (!vencimento) { Swal.showValidationMessage('Informe o vencimento'); return false; }

                let dados = {
                    id: id || '',
                    idClientes: cliente,
                    idEmpresa: empresa,
                    idUnidade: unidade,
                    idSubUnidade: $('#swal-subunidade').val() || '',
                    idCategoria: $('#swal-categoria').val() || '',
                    idSubCategoria: $('#swal-subcategoria').val() || '',
                    valor: valor,
                    vencimento: vencimento,
                    competencia: $('#swal-competencia').val(),
                    unidade: '',
                    observacoes: $('#swal-obs').val()
                };

                if (!editando) {
                    let repetir = document.getElementById('swal-repetir').checked;
                    dados.repetir = repetir ? '1' : '';
                    dados.tipoRepeticao = document.getElementById('swal-tipo-repeticao').value;
                    dados.qtdRepeticoes = document.getElementById('swal-qtd-repeticoes').value;
                }

                return dados;
            }
        }).then(result => {
            if (result.isConfirmed) {
                let data = new URLSearchParams(result.value);
                fetch(siteUrl + 'contasReceber/salvar', {
                    method: 'POST',
                    body: data,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            Swal.fire('Salvo!', res.message, 'success')
                                .then(() => {
                                    carregarContasReceber();
                                    carregarDashboard();
                                });
                        } else {
                            Swal.fire('Erro', res.message || 'Falha ao salvar.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Erro', 'Erro de conexão.', 'error'));
            }
        });
    });
}

let carregarUnidadesModal = function (empresaId) {
    return carregarUnidades(empresaId, 'swal-unidade').then(() => {
        document.getElementById('swal-subunidade').innerHTML = '<option value="">Selecione um departamento</option>';
    });
};

let carregarSubUnidadesModal = function (unidadeId) {
    return carregarSubUnidades(unidadeId, 'swal-subunidade');
};

let carregarSubCategoriaModal = function (idCategoria) {
    return carregarSubCategoriasPorCategoria(idCategoria, 'swal-subcategoria');
};

function carregarDadosEdicao(id, popup) {
    fetch(siteUrl + 'contasReceber/getDados?id=' + id)
        .then(res => res.json())
        .then(d => {
            $('#swal-cliente').val(String(d.idClientes)).trigger('change');
            $('#swal-empresa').val(String(d.idEmpresa)).trigger('change');
            $('#swal-categoria').val(String(d.idCategoria || '')).trigger('change');

            carregarSubCategoriaModal(d.idCategoria).then(() => {
                $('#swal-subcategoria').val(String(d.idSubCategoria || '')).trigger('change');
            });

            carregarUnidadesModal(d.idEmpresa).then(() => {
                $('#swal-unidade').val(String(d.idUnidade)).trigger('change');
                carregarSubUnidadesModal(d.idUnidade).then(() => {
                    $('#swal-subunidade').val(String(d.idSubUnidade || '')).trigger('change');
                });
            });

            let valorNum = parseFloat(d.valor) || 0;
            $('#swal-valor').val(valorNum.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            $('#swal-vencimento').val(d.vencimento || '');
            $('#swal-competencia').val(d.competencia || '');
            $('#swal-obs').val(d.observacoes || '');
        });
}

function editarConta(id) {
    abrirModal(id);
}

function excluirConta(id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Tem certeza que deseja excluir esta conta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            let data = new URLSearchParams();
            data.append('id', id);
            fetch(siteUrl + 'contasReceber/excluir', {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('Excluído!', res.message, 'success');
                    carregarContasReceber();
                    carregarDashboard();
                });
        }
    });
}

function importarCSV(file) {
    if (!file) return;

    let reader = new FileReader();
    reader.onload = function (e) {
        let text = e.target.result;
        let lines = text.split('\n');
        if (lines.length < 2) {
            Swal.fire('Erro', 'CSV vazio ou formato inválido.', 'error');
            return;
        }

        let headers = lines[0].split(';').map(h => h.trim().toLowerCase());
        let required = ['cliente', 'valor', 'vencimento'];
        for (let r of required) {
            if (!headers.includes(r)) {
                Swal.fire('Erro', 'Coluna "' + r + '" não encontrada no CSV.', 'error');
                return;
            }
        }

        let imported = 0;
        let errors = [];

        for (let i = 1; i < lines.length; i++) {
            let line = lines[i].trim();
            if (!line) continue;

            let cols = line.split(';');
            let row = {};
            headers.forEach((h, idx) => {
                row[h] = (cols[idx] || '').trim();
            });

            if (!row.cliente || !row.valor || !row.vencimento) {
                errors.push('Linha ' + (i + 1) + ': dados incompletos');
                continue;
            }

            let data = new URLSearchParams();
            data.append('idClientes', row.cliente);
            data.append('idUsuarios', '');
            data.append('idEmpresa', '');
            data.append('idUnidade', '');
            data.append('idSubUnidade', '');
            data.append('valor', row.valor.replace(',', '.'));
            data.append('vencimento', row.vencimento);
            data.append('unidade', row.unidade || '');
            data.append('idCategoria', row.categoria || '');
            data.append('observacoes', row.observacoes || '');

            fetch(siteUrl + 'contasReceber/salvar', {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.json()).then(res => {
                if (res.success) imported++;
            });
        }

        Swal.fire('Importação concluída', imported + ' conta(s) importada(s).' + (errors.length ? '\n' + errors.length + ' erro(s).' : ''), 'info');
        carregarContasReceber();
        carregarDashboard();
    };
    reader.readAsText(file, 'UTF-8');

    document.getElementById('csvFileInput').value = '';
}

window.carregarUnidadesModal = carregarUnidadesModal;
window.carregarSubUnidadesModal = carregarSubUnidadesModal;
window.carregarSubCategoriaModal = carregarSubCategoriaModal;
