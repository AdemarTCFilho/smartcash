<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ContasReceber extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('contasReceber_model');
        $this->data['menuContasReceber'] = 'Contas a Receber';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vContasReceber')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Contas a Receber.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/contasReceber/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->contasReceber_model->count('contasReceber');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'contasReceber/contasReceber';
        return $this->layout();
    }

}