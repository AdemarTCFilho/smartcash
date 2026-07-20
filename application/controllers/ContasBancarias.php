<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ContasBancarias extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('contasBancarias_model');
        $this->data['menuContasBancarias'] = 'Contas Bancárias';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vContasBancarias')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Contas Bancárias.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/contasBancarias/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->contasBancarias_model->count('contasBancarias');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'contasBancarias/contasBancarias';
        return $this->layout();
    }

}