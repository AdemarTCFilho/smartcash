<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class GrupoSaudeMaster extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('grupoSaudeMaster_model');
        $this->data['menuGrupoSaudeMaster'] = 'Grupo Saúde Master';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vGrupoSaudeMaster')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Grupo Saúde Master.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/grupoSaudeMaster/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->cidadao_model->count('cidadao');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'grupoSaudeMaster/grupoSaudeMaster';
        return $this->layout();
    }

}