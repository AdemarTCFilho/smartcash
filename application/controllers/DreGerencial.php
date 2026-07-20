<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class DreGerencial extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('dreGerencial_model');
        $this->data['menuDreGerencial'] = 'Contas a Pagar';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vDreGerencial')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Contas a Pagar.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/dreGerencial/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->dreGerencial_model->count('dreGerencial');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'dreGerencial/dreGerencial';
        return $this->layout();
    }

}