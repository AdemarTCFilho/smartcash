 <div class="span12" style="margin-left: 0; margin-top: 0">
    <div class="span12" style="margin-left: 0">
        <form action="<?php echo current_url() ?>">
            <div class="span10" style="margin-left: 0">
                <input type="text" class="span12" name="termo" placeholder="Digite o termo a pesquisar"/>
            </div>
            <div class="span2">
                <button class="button btn btn-mini btn-warning">
                  <span class="button__icon"><i class='bx bx-search-alt'></i></span> <span class="button__text2">Pesquisar</span></button>
            </div>
        </form>
    </div>
    <div class="span12" style="margin-left: 0; margin-top: 0">
        <!--Cidadãos-->
        <div class="span6" style="margin-left: 0; margin-top: 0">
            <div class="widget-box" style="min-height: 200px">
                <div class="widget-title" style="margin: -20px 0 0">
                    <span class="icon">
                        <i class="fas fa-shopping-bag"></i>
                    </span>
                    <h5>Cidadãos</h5>
                </div>
                <div class="widget-content nopadding tab-content">
                    <table class="table table-bordered ">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>CNS</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($cidadaos == null) {
                            echo '<tr><td colspan="4">Nenhum cidadão foi encontrado.</td></tr>';
                        }
                        foreach ($cidadaos as $r) {
                            echo '<tr>';
                            echo '<td>' . $r->idCidadao . '</td>';
                            echo '<td>' . $r->nome . '</td>';
                            echo '<td>' . $r->cpf . '</td>';
                            echo '<td>' . $r->cns . '</td>';
                            echo '<td>';
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCidadao')) {
                                echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/cidadao/visualizar/' . $r->idCidadao . '" class="btn-nwe" title="Ver mais detalhes"><i class="bx bx-show"></i></a>';
                            }
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCidadao')) {
                                echo '<a href="' . base_url() . 'index.php/cidadao/editar/' . $r->idCidadao . '" class="btn-nwe3" title="Editar cidadão"><i class="bx bx-edit"></i></a>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        } ?>
                        <tr>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--Profissionais de Saúde-->
        <div class="span6">
            <div class="widget-box" style="min-height: 200px">
                <div class="widget-title" style="margin: -20px 0 0">
                    <span class="icon">
                        <i class="fas fa-user"></i>
                    </span>
                    <h5>Profissionais de Saúde</h5>
                </div>
                <div class="widget-content nopadding tab-content">
                    <table class="table table-bordered ">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Conselho</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($profissionaisSaude == null) {
                            echo '<tr><td colspan="4">Nenhum profissional de saúde foi encontrado.</td></tr>';
                        }
                        foreach ($profissionaisSaude as $r) {
                            echo '<tr>';
                            echo '<td>' . $r->idProfissional . '</td>';
                            echo '<td>' . $r->nome . '</td>';
                            echo '<td>' . $r->conselho . '</td>';
                            echo '<td>';
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vProfissionalSaude')) {
                                echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/AgendSolicitante/visualizar/' . $r->idProfissional . '" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>';
                            }
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eProfissionalSaude')) {
                                echo '<a href="' . base_url() . 'index.php/AgendSolicitante/editar/' . $r->idProfissional . '" class="btn btn-info tip-top" title="Editar Profissional"><i class="fas fa-edit"></i></a>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                        <tr>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="span12" style="margin-left: 0; margin-top: 0">
        <!--Unidade Solicitante-->
        <div class="span6" style="margin-left: 0; margin-top: 0">
            <div class="widget-box" style="min-height: 200px">
                <div class="widget-title" style="margin: -20px 0 0">
                    <span class="icon">
                        <i class="fas fa-shopping-bag"></i>
                    </span>
                    <h5>Unidade Solicitante</h5>
                </div>
                <div class="widget-content nopadding tab-content">
                    <table class="table table-bordered ">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Endereço</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($unidade_solicitante == null) {
                            echo '<tr><td colspan="4">Nenhuma unidade solicitante foi encontrada.</td></tr>';
                        }
                        foreach ($unidade_solicitante as $r) {
                            echo '<tr>';
                            echo '<td>' . $r->idUnidadeSolicitante . '</td>';
                            echo '<td>' . $r->nome . '</td>';
                            echo '<td>' . $r->endereco . '</td>';
                            echo '<td>';
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vUnidadeSolicitante')) {
                                echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/UnidadeSolicitante/visualizar/' . $r->idUnidadeSolicitante . '" class="btn-nwe" title="Ver mais detalhes"><i class="bx bx-show"></i></a>';
                            }
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eUnidadeSolicitante')) {
                                echo '<a href="' . base_url() . 'index.php/UnidadeSolicitante/editar/' . $r->idUnidadeSolicitante . '" class="btn-nwe3" title="Editar unidade solicitante"><i class="bx bx-edit"></i></a>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        } ?>
                        <tr>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--Unidade Executante-->
        <div class="span6">
            <div class="widget-box" style="min-height: 200px">
                <div class="widget-title" style="margin: -20px 0 0">
                    <span class="icon">
                        <i class="fas fa-user"></i>
                    </span>
                    <h5>Unidade Executante</h5>
                </div>
                <div class="widget-content nopadding tab-content">
                    <table class="table table-bordered ">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Endereço</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($unidade_executante == null) {
                            echo '<tr><td colspan="4">Nenhuma unidade executante foi encontrada.</td></tr>';
                        }
                        foreach ($unidade_executante as $r) {
                            echo '<tr>';
                            echo '<td>' . $r->idUnidadeExecutante . '</td>';
                            echo '<td>' . $r->nome . '</td>';
                            echo '<td>' . $r->endereco . '</td>';
                            echo '<td>';
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vUnidadeExecutante')) {
                                echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/UnidadeExecutante/visualizar/' . $r->idUnidadeExecutante . '" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>';
                            }
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eUnidadeExecutante')) {
                                echo '<a href="' . base_url() . 'index.php/UnidadeExecutante/editar/' . $r->idUnidadeExecutante . '" class="btn btn-info tip-top" title="Editar Unidade Executante"><i class="fas fa-edit"></i></a>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                        <tr>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
