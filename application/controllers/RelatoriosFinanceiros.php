<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class RelatoriosFinanceiros extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('relatoriosFinanceiros_model');
        $this->data['menuRelatoriosFinanceiros'] = 'Relatórios Financeiros';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vRelatoriosFinanceiros')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Relatórios Financeiros.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/relatoriosFinanceiros/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->relatoriosFinanceiros_model->count('relatoriosFinanceiros');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'relatoriosFinanceiros/relatoriosFinanceiros';
        return $this->layout();
    }

}