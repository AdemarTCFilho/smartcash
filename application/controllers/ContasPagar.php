<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ContasPagar extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('contasPagar_model');
        $this->data['menuContasPagar'] = 'Contas a Pagar';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vContasPagar')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Contas a Pagar.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/contasPagar/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->contasPagar_model->count('contasPagar');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'contasPagar/contasPagar';
        return $this->layout();
    }

}