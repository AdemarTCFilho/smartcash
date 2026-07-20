<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class BaixasFinanceiras extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('baixasFinanceiras_model');
        $this->data['menuBaixasFinanceiras'] = 'Baixas Financeiras';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vBaixasFinanceiras')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Baixas Financeiras.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/baixasFinanceiras/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->baixasFinanceiras_model->count('baixasFinanceiras');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'baixasFinanceiras/baixasFinanceiras';
        return $this->layout();
    }

}