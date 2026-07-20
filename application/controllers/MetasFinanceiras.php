<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MetasFinanceiras extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('etasFinanceiras_model');
        $this->data['menuMetasFinanceiras'] = 'Metas Financeiras';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vMetasFinanceiras')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Metas Financeiras.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/metasFinanceiras/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->cidadao_model->count('cidadao');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'metasFinanceiras/metasFinanceiras';
        return $this->layout();
    }

}