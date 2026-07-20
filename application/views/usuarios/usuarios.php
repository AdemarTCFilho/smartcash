<div class="new122">
  <a href="<?php echo base_url() ?>index.php/usuarios/adicionar" class="button btn btn-success" style="max-width: 220px">
  <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2">Adicionar Usuário</span></a>

<div class="widget-box">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon">
            <i class="fas fa-user"></i>
        </span>
        <h5>Usuários</h5>

    </div>
    <div class="widget-content nopadding tab-content">
        <table id="tabela" class="table table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>E-mail</th>
                    <th>Unidade Solicitante</th>
                    <th>Data de Expiração</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (!$results) {
                        echo '<tr>
                                <td colspan="7">Nenhum Usuário Cadastrado</td>
                            </tr>';
                    }
                    foreach ($results as $r) {

                        // Obter permissão (ID) do usuário logado
                        $permissao_id_usuario_logado = $this->session->userdata('permissao');
                        
                        // Lógica de permissões para edição
                        $pode_editar = false;
                        
                        if ($permissao_id_usuario_logado == '1') {
                            // SuperAdmin (ID 1 = Administrador) pode editar todos
                            $pode_editar = true;
                        } else {
                            // Para outros usuários (Admin, etc), não podem editar usuários com permissão "Administrador"
                            if ($r->permissao != 'Administrador') {
                                $pode_editar = true;
                            }
                        }

                        echo '<tr>';
                            if ($pode_editar) {
                                echo '<td>' . $r->idUsuarios . '</td>';
                                echo '<td>' . $r->nome . '</td>';
                                echo '<td>' . $r->permissao . '</td>';
                                echo '<td>' . $r->email . '</td>';
                                echo '<td>' . ($r->nome_unidade_solicitante ? $r->nome_unidade_solicitante : 'Não informado') . '</td>';
                                echo '<td>' . date('d/m/Y', strtotime($r->dataExpiracao)) . '</td>';
                                echo '<td>';
                                echo '<a href="' . base_url() . 'index.php/usuarios/editar/' . $r->idUsuarios . '" class="btn-nwe3" title="Editar Usuario"><i class="bx bx-edit"></i></a>';
                                echo '</td>';
                            }
                        echo '</tr>';
                    } ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<?php echo $this->pagination->create_links(); ?>
