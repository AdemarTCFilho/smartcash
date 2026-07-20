<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class RankingReceitasDespesas extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        // $this->load->model('rankingReceitasDespesas_model');
        $this->data['menuRankingReceitasDespesas'] = 'Ranking de Receitas & Despesas';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vRankingReceitasDespesas')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Ranking de Receitas & Despesas.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/rankingReceitasDespesas/gerenciar/';
        // $this->data['configuration']['total_rows'] = $this->cidadao_model->count('cidadao');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['view'] = 'rankingReceitasDespesas/rankingReceitasDespesas';
        return $this->layout();
    }

}