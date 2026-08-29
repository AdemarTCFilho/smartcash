(function () {
    'use strict';

    var arquivoSelecionado = null;
    var tabAtual = 'C';

    function formatMoney(valor) {
        return 'R$ ' + parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function initTabs() {
        var tabs = document.querySelectorAll('.dre-tab');
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) { t.classList.remove('active'); });
                tab.classList.add('active');

                document.querySelectorAll('.dre-tab-content').forEach(function (c) { c.classList.remove('active'); });
                var target = tab.getAttribute('data-tab');
                if (target === 'credito') {
                    document.getElementById('tabCredito').classList.add('active');
                    carregarRegistros('C');
                } else {
                    document.getElementById('tabDebito').classList.add('active');
                    carregarRegistros('D');
                }
            });
        });
    }

    function initModal() {
        var btnImportar = document.getElementById('btnImportar');
        var btnFechar = document.getElementById('btnFecharModal');
        var btnCancelar = document.getElementById('btnCancelarModal');
        var modal = document.getElementById('modalImportar');
        var uploadArea = document.getElementById('uploadArea');
        var fileInput = document.getElementById('fileInput');
        var fileInfo = document.getElementById('fileInfo');
        var fileName = document.getElementById('fileName');
        var btnRemover = document.getElementById('btnRemoverArquivo');
        var btnConfirmar = document.getElementById('btnConfirmarImport');

        btnImportar.addEventListener('click', function () {
            modal.style.display = 'flex';
            resetUpload();
        });

        btnFechar.addEventListener('click', function () { modal.style.display = 'none'; });
        btnCancelar.addEventListener('click', function () { modal.style.display = 'none'; });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) modal.style.display = 'none';
        });

        uploadArea.addEventListener('click', function () { fileInput.click(); });

        uploadArea.addEventListener('dragover', function (e) {
            e.preventDefault();
            uploadArea.classList.add('dre-upload-dragover');
        });

        uploadArea.addEventListener('dragleave', function () {
            uploadArea.classList.remove('dre-upload-dragover');
        });

        uploadArea.addEventListener('drop', function (e) {
            e.preventDefault();
            uploadArea.classList.remove('dre-upload-dragover');
            if (e.dataTransfer.files.length > 0) {
                selecionarArquivo(e.dataTransfer.files[0]);
            }
        });

        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                selecionarArquivo(fileInput.files[0]);
            }
        });

        btnRemover.addEventListener('click', function () { resetUpload(); });

        btnConfirmar.addEventListener('click', function () {
            if (!arquivoSelecionado) return;
            importarArquivo(arquivoSelecionado);
        });

        function resetUpload() {
            arquivoSelecionado = null;
            fileInput.value = '';
            uploadArea.style.display = 'flex';
            fileInfo.style.display = 'none';
            btnConfirmar.disabled = true;
        }

        function selecionarArquivo(file) {
            var ext = file.name.split('.').pop().toLowerCase();
            if (['csv', 'xls', 'xlsx'].indexOf(ext) === -1) {
                showToast('Formato não permitido. Use CSV, XLS ou XLSX.', 'error');
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                showToast('Arquivo muito grande. Máximo 10MB.', 'error');
                return;
            }
            arquivoSelecionado = file;
            fileName.textContent = file.name;
            uploadArea.style.display = 'none';
            fileInfo.style.display = 'flex';
            btnConfirmar.disabled = false;
        }
    }

    function importarArquivo(file) {
        var btnConfirmar = document.getElementById('btnConfirmarImport');
        btnConfirmar.disabled = true;
        btnConfirmar.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Importando...';

        var formData = new FormData();
        formData.append('arquivo', file);

        fetch(siteUrl + 'dreImportacao/importarPlanilha', {
            method: 'POST',
            body: formData
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            document.getElementById('modalImportar').style.display = 'none';
            if (res.success) {
                showToast(res.message, 'success');
                carregarContadores();
                var tabAtiva = document.querySelector('.dre-tab.active').getAttribute('data-tab');
                carregarRegistros(tabAtiva === 'credito' ? 'C' : 'D');
            } else {
                showToast(res.message, 'error');
            }
        })
        .catch(function () {
            showToast('Erro ao importar arquivo.', 'error');
        })
        .finally(function () {
            btnConfirmar.disabled = false;
            btnConfirmar.innerHTML = 'Importar';
        });
    }

    function carregarContadores() {
        fetch(siteUrl + 'dreImportacao/contarRegistros')
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.success) {
                document.getElementById('countCredito').textContent = res.creditos;
                document.getElementById('countDebito').textContent = res.debitos;
            }
        });
    }

    function carregarRegistros(status) {
        tabAtual = status;
        var tbody = status === 'C' ? document.getElementById('bodyCredito') : document.getElementById('bodyDebito');

        fetch(siteUrl + 'dreImportacao/listarRegistros?status=' + status)
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (!res.success || !res.data.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="dre-empty">Nenhum registro encontrado</td></tr>';
                return;
            }

            var html = '';
            res.data.forEach(function (reg) {
                html += '<tr data-id="' + reg.idImportacao + '">';
                html += '<td>' + (reg.data ? formatDate(reg.data) : '-') + '</td>';
                html += '<td class="dre-td-historico" title="' + escapeHtml(reg.historico) + '">' + escapeHtml(reg.historico) + '</td>';
                html += '<td class="dre-td-valor">' + formatMoney(reg.valor) + '</td>';
                html += '<td class="dre-td-detalhe" title="' + escapeHtml(reg.detalhamentoHist) + '">' + escapeHtml(reg.detalhamentoHist) + '</td>';
                html += '<td><select class="dre-select2-categoria" data-id="' + reg.idImportacao + '" data-current="' + (reg.idSubCategoria || '') + '">';
                html += '<option value="">Selecione...</option>';
                if (reg.idSubCategoria && reg.nomeSubCategoria) {
                    html += '<option value="' + reg.idSubCategoria + '" selected>' + escapeHtml(reg.nomeSubCategoria) + ' (' + escapeHtml(reg.nomeCategoria) + ')</option>';
                }
                html += '</select></td>';
                html += '<td><select class="dre-select2-unidade" data-id="' + reg.idImportacao + '" data-current="' + (reg.idUnidade || '') + '">';
                html += '<option value="">Selecione...</option>';
                if (reg.idUnidade && reg.nomeUnidade) {
                    html += '<option value="' + reg.idUnidade + '" selected>' + escapeHtml(reg.nomeUnidade) + '</option>';
                }
                html += '</select></td>';
                html += '</tr>';
            });

            tbody.innerHTML = html;
            initSelect2();
        });
    }

    function initSelect2() {
        if (typeof $ === 'undefined' || !$.fn.select2) {
            setTimeout(initSelect2, 100);
            return;
        }

        var tipoCategoria = tabAtual === 'C' ? 'ENTRADA' : 'SAIDA';
        var categoriaMap = {};

        $('.dre-select2-categoria').select2({
            width: '100%',
            placeholder: 'Selecione a despesa...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: siteUrl + 'dreImportacao/listarCategorias',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return { q: params.term || '', tipo: tipoCategoria };
                },
                processResults: function (data) {
                    data.results.forEach(function (item) {
                        categoriaMap[item.id] = item.idCategoria;
                    });
                    return { results: data.results };
                }
            }
        });

        $('.dre-select2-unidade').select2({
            width: '100%',
            placeholder: 'Selecione o centro...',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: siteUrl + 'dreImportacao/listarUnidades',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return { q: params.term || '' };
                },
                processResults: function (data) {
                    return { results: data.results };
                }
            }
        });

        $('.dre-select2-categoria').on('change', function () {
            var id = $(this).data('id');
            var idSubCategoria = $(this).val();
            var idCategoria = idSubCategoria ? (categoriaMap[idSubCategoria] || null) : null;
            salvarMapeamento(id, idCategoria, idSubCategoria, null);
        });

        $('.dre-select2-unidade').on('change', function () {
            var id = $(this).data('id');
            var idUnidade = $(this).val();
            salvarMapeamento(id, null, null, idUnidade);
        });
    }

    function salvarMapeamento(id, idCategoria, idSubCategoria, idUnidade) {
        var formData = new URLSearchParams();
        formData.append('idImportacao', id);
        if (idCategoria !== null && idCategoria !== undefined) {
            formData.append('idCategoria', idCategoria);
        }
        if (idSubCategoria !== null && idSubCategoria !== undefined) {
            formData.append('idSubCategoria', idSubCategoria);
        }
        if (idUnidade !== null && idUnidade !== undefined) {
            formData.append('idUnidade', idUnidade);
        }

        fetch(siteUrl + 'dreImportacao/salvarMapeamento', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.success) {
                showToast('Mapeamento salvo!', 'success');
            } else {
                showToast(res.message || 'Erro ao salvar.', 'error');
            }
        })
        .catch(function () {
            showToast('Erro ao salvar mapeamento.', 'error');
        });
    }

    function initLimpar() {
        document.getElementById('btnLimpar').addEventListener('click', function () {
            Swal.fire({
                title: 'Limpar registros',
                text: 'Deseja excluir todos os registros importados?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6b63ff',
                cancelButtonColor: '#30304d',
                confirmButtonText: 'Sim, limpar',
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (!result.isConfirmed) return;

                fetch(siteUrl + 'dreImportacao/excluirImportacao', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        showToast(res.message, 'success');
                        carregarContadores();
                        document.getElementById('bodyCredito').innerHTML = '<tr><td colspan="6" class="dre-empty">Nenhum registro importado</td></tr>';
                        document.getElementById('bodyDebito').innerHTML = '<tr><td colspan="6" class="dre-empty">Nenhum registro importado</td></tr>';
                    } else {
                        showToast(res.message, 'error');
                    }
                });
            });
        });
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        var parts = dateStr.split('-');
        if (parts.length === 3) {
            return parts[2] + '/' + parts[1] + '/' + parts[0];
        }
        return dateStr;
    }

    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function showToast(message, type) {
        var existing = document.querySelector('.dre-toast');
        if (existing) existing.remove();

        var toast = document.createElement('div');
        toast.className = 'dre-toast dre-toast-' + type;
        toast.innerHTML = '<i class="fa fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
        document.body.appendChild(toast);

        setTimeout(function () { toast.classList.add('dre-toast-show'); }, 10);
        setTimeout(function () {
            toast.classList.remove('dre-toast-show');
            setTimeout(function () { toast.remove(); }, 300);
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function () {
        initTabs();
        initModal();
        initLimpar();
        carregarContadores();
        carregarRegistros('C');
    });
})();
