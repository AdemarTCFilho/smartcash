<!--sidebar-menu-->
<nav id="sidebar">
    <div id="newlog">
        <!-- <div class="icon2">
            <img src="<?php echo base_url() ?>assets/img/logo-two.png">
        </div> -->
        <div class="title1">
            <?= $configuration['app_theme'] == 'white' ? '<img src="' . base_url() . 'assets/img/smart-cash-logo.png">' : '<img src="' . base_url() . 'assets/img/smart-cash-logo.png">'; ?>
        </div>
    </div>
    <a href="#" class="visible-phone">
        <div class="mode">
            <div class="moon-menu">
                <i class='bx bx-menu iconX open-2'></i>
                <i class='bx bx-x iconX close-2'></i>
            </div>
        </div>
    </a>
    <!-- Start Pesquisar-->
    <!-- <li class="search-box">
        <form style="display: flex" action="<?= site_url('mapos/pesquisar') ?>">
            <button style="background: transparent;border: transparent" type="submit" class="tip-bottom" title="">
                <i class='bx bx-search iconX'></i></button>
            <input type="search" name="termo" placeholder="Pesquise aqui...">
            <span class="title-tooltip">Pesquisar</span>
        </form>
    </li> -->
    <!-- End Pesquisar-->

    <div class="menu-bar"><br/>
        <div class="menu">

            <ul class="menu-links" style="position: relative;width: 97%;">
                <li class="<?php if (isset($menuPainel)) {
                    echo 'active';
                }; ?>">
                    <a class="tip-bottom" title="" href="<?= base_url('mapos') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                        <span class="title nav-title">Dashboard</span>
                        <span class="title-tooltip">Início</span>
                    </a>
                </li>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) { ?>
                    <li class="<?php if (isset($menuClientes)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('clientes') ?>"><i class='bx bx-group iconX'></i>
                            <span class="title">Cliente / Fornecedor</span>
                            <span class="title-tooltip">Clientes</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vGrupoSaudeMaster')) { ?>
                    <li class="<?php if (isset($menuGrupoSaudeMaster)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('grupoSaudeMaster') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Grupo Saúde Master</span>
                            <span class="title-tooltip">Grupo Saúde Master</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vProjetadoRealizado')) { ?>
                    <li class="<?php if (isset($menuProjetadoRealizado)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('projetadoRealizado') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Projetado x Realizado</span>
                            <span class="title-tooltip">Projetado x Realizado</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vMetasFinanceiras')) { ?>
                    <li class="<?php if (isset($menuMetasFinanceiras)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('metasFinanceiras') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Metas Financeiras</span>
                            <span class="title-tooltip">Metas Financeiras</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vRankingReceitasDespesas')) { ?>
                    <li class="<?php if (isset($menuRankingReceitasDespesas)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="Ranking de Receitas & Despesas" href="<?= site_url('rankingReceitasDespesas') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title" title="Ranking de Receitas & Despesas">Ranking por Unidade</span>
                            <span class="title-tooltip">Ranking por Unidade</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vContasReceber')) { ?>
                    <li class="<?php if (isset($menuContasReceber)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="Ranking de Receitas & Despesas" href="<?= site_url('contasReceber') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title" title="Ranking de Receitas & Despesas">Contas a Receber</span>
                            <span class="title-tooltip">Contas a Receber</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vContasPagar')) { ?>
                    <li class="<?php if (isset($menuContasPagar)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('contasPagar') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Contas a Pagar</span>
                            <span class="title-tooltip">Contas a Pagar</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vBaixasFinanceiras')) { ?>
                    <li class="<?php if (isset($menuBaixasFinanceiras)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('baixasFinanceiras') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Baixas Financeiras</span>
                            <span class="title-tooltip">Baixas Financeiras</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vConciliacaoBancaria')) { ?>
                    <li class="<?php if (isset($menuConciliacaoBancaria)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('conciliacaoBancaria') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Conciliação Bancária</span>
                            <span class="title-tooltip">Conciliação Bancária</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vContasBancarias')) { ?>
                    <li class="<?php if (isset($menuContasBancarias)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('contasBancarias') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Contas Bancárias</span>
                            <span class="title-tooltip">Contas Bancárias</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vRelatoriosFinanceiros')) { ?>
                    <li class="<?php if (isset($menuRelatoriosFinanceiros)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('relatoriosFinanceiros') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Relatórios</span>
                            <span class="title-tooltip">Relatórios</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vFluxoCaixa')) { ?>
                    <li class="<?php if (isset($menuFluxoCaixa)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('fluxoCaixa') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Fluxo de Caixa</span>
                            <span class="title-tooltip">Fluxo de Caixa</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vDreGerencial')) { ?>
                    <li class="<?php if (isset($menuDreGerencial)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('dreGerencial') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">DRE Gerencial</span>
                            <span class="title-tooltip">DRE Gerencial</span>
                        </a>
                    </li>
                <?php } ?>

                 <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vBoardExecutivo')) { ?>
                    <li class="<?php if (isset($menuBoardExecutivo)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('boardExecutivo') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Board Executivo</span>
                            <span class="title-tooltip">Board Executivo</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vVigiaIa')) { ?>
                    <li class="<?php if (isset($menuVigiaIa)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('vigiaIa') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Vigia IA</span>
                            <span class="title-tooltip">Vigia IA</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vEmpresasUnidades')) { ?>
                    <li class="<?php if (isset($menuvEmpresasUnidades)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('empresasUnidades') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Empresas / Unidades</span>
                            <span class="title-tooltip">Empresas / Unidades</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vClientes')) { ?>
                    <li class="<?php if (isset($menuvClientes)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('clientes') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Clientes</span>
                            <span class="title-tooltip">Clientes</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCategoria')) { ?>
                    <li class="<?php if (isset($menuvCategoria)) {
                        echo 'active';
                    }; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('categoria') ?>"><i class='fa fa-circle iconX' style="font-size:10px;color:#201a61;"></i>
                            <span class="title">Categoria</span>
                            <span class="title-tooltip">Categoria</span>
                        </a>
                    </li>
                <?php } ?>

            </ul>
        </div>

        <div class="botton-content">
            <li class="">
                <a class="tip-bottom" title="" href="<?= site_url('login/sair'); ?>">
                    <i class='bx bx-log-out-circle iconX'></i>
                    <span class="title">Sair</span>
                    <span class="title-tooltip">Sair</span>
                </a>
            </li><br/>
        </div>
    </div>
</nav>
<!--End sidebar-menu-->

<style>
.submenu {
    position: relative;
}

.submenu-list {
    display: none;
    width: 100%;
    background-color: rgba(185, 185, 185, 0.15);
    margin: 0;
    padding: 0;
    list-style: none;
    border-left: 3px solid rgba(255,255,255,0.2);
}

.submenu-list li {
    margin: 0;
    padding: 0;
    width: 100%;
    display: block;
    list-style: none;
}

.submenu-list li a {
    padding: 10px 15px 10px 20px;
    font-size: 13px;
    display: block;
    width: 100%;
    box-sizing: border-box;
    text-decoration: none;
    color: inherit;
    border: none;
    background: transparent;
}

.submenu-list li a:hover {
    background-color: rgba(255,255,255,0.1);
}

.submenu-list li.active a {
    background-color: rgba(255,255,255,0.2);
    font-weight: bold;
}

.submenu .submenu-arrow {
    float: right;
    transition: transform 0.3s ease;
    margin-top: 2px;
}

.submenu.open .submenu-arrow {
    transform: rotate(180deg);
}

/* Força o submenu a aparecer como bloco */
.submenu.open + .submenu-list {
    display: block !important;
}
</style>

<script>
function toggleSubmenu(element) {
    const submenu = element.closest('.submenu');
    let submenuList = submenu ? submenu.nextElementSibling : null;
    
    // Método alternativo caso nextElementSibling não funcione
    if (!submenuList || !submenuList.classList.contains('submenu-list')) {
        submenuList = document.querySelector('.submenu-list');
    }
    
    if (submenu && submenuList) {
        const isOpen = submenu.classList.contains('open');
        
        if (isOpen) {
            submenu.classList.remove('open');
            submenuList.style.display = 'none';
        } else {
            submenu.classList.add('open');
            submenuList.style.display = 'block';
        }
        
        // Previne o comportamento padrão do link
        return false;
    }
}

// Auto-abrir submenu se estiver na página do procedimento, teto ou escala
document.addEventListener('DOMContentLoaded', function() {
    const activeSubmenuItem = document.querySelector('.submenu-list .active');
    if (activeSubmenuItem) {
        const submenuList = activeSubmenuItem.closest('.submenu-list');
        const submenu = document.querySelector('.submenu');
        
        if (submenu && submenuList) {
            submenu.classList.add('open');
            submenuList.style.display = 'block';
        }
    }
});

// Adiciona event listener alternativo
document.addEventListener('DOMContentLoaded', function() {
    const submenuLink = document.querySelector('.submenu a');
    if (submenuLink) {
        submenuLink.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleSubmenu(this);
        });
    }
});
</script>
