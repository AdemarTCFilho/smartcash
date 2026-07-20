<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class FluxoCaixa extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('fluxoCaixa_model');
        $this->data['menuFluxoCaixa'] = 'Contas a Pagar';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vFluxoCaixa')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Contas a Pagar.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/fluxoCaixa/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->fluxoCaixa_model->count('fluxoCaixa');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'fluxoCaixa/fluxoCaixa';
        return $this->layout();
    }

}