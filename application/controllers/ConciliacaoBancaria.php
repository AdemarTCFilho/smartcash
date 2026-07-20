<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ConciliacaoBancaria extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('conciliacaoBancaria_model');
        $this->data['menuConciliacaoBancaria'] = 'Conciliação Bancária';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vConciliacaoBancaria')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Conciliação Bancária.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/conciliacaoBancaria/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->conciliacaoBancaria_model->count('conciliacaoBancaria');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'conciliacaoBancaria/conciliacaoBancaria';
        return $this->layout();
    }

}