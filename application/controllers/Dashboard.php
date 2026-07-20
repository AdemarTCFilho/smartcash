<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('cidadao_model');
        $this->data['menuCidadao'] = 'Cidadão';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vCidadao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar cidadão.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/cidadao/gerenciar/';
        $this->data['configuration']['total_rows'] = $this->cidadao_model->count('cidadao');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->cidadao_model->get('cidadao', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'cidadao/cidadao';
        return $this->layout();
    }

    public function adicionar()
    {

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aCidadao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar cidadão.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        // Configurar regras de validação
        $this->form_validation->set_rules('nome', 'Nome do cidadão', 'required');
        $this->form_validation->set_rules('cpf', 'CPF', 'required|exact_length[14]');
        $this->form_validation->set_rules('cep', 'CEP', 'exact_length[9]');
        // $this->form_validation->set_rules('cns', 'CNS', 'required');
        // $this->form_validation->set_rules('telefone', 'Telefone', 'required');
        // $this->form_validation->set_rules('data_nasc', 'Data de Nascimento', 'required');

        // Configurar mensagens de erro personalizadas
        $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
        $this->form_validation->set_message('exact_length', 'O campo %s deve conter exatamente {param} caracteres.');

        // Inicializar dados vazios para o formulário
        $this->data['result'] = (object) [
            'nome' => '',
            'cpf' => '',
            'cns' => '',
            'telefone' => '',
            'data_nasc' => '',
            'sexo' => 'MASCULINO', // Padrão: Masculino
            'filiacao' => '',
            'endereco' => '',
            'numero' => '',
            'cep' => '',
            'bairro' => '',
            'cidade' => '',
            'uf' => '',
            'situacao' => '1' // Padrão: Ativo
        ];

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $nome = $this->input->post('nome');
            $endereco = $this->input->post('endereco');
            $cpf = $this->input->post('cpf');
            $cns = $this->input->post('cns');
            $telefone = $this->input->post('telefone');
            $data_nasc = $this->input->post('data_nasc');
            $sexo = $this->input->post('sexo');
            $filiacao = $this->input->post('filiacao');
            $numero = $this->input->post('numero');
            $cep = $this->input->post('cep');
            $bairro = $this->input->post('bairro');
            $cidade = $this->input->post('cidade');
            $uf = $this->input->post('uf');
            $situacao = $this->input->post('situacao');

            $data = [
                'nome' => $nome,
                'endereco' => $endereco,
                'cpf' => $cpf,
                'cns' => $cns,
                'telefone' => $telefone,
                'data_nasc' => $data_nasc,
                'sexo' => $sexo,
                'filiacao' => $filiacao,
                'numero' => $numero,
                'cep' => $cep,
                'bairro' => $bairro,
                'cidade' => $cidade,
                'uf' => $uf,
                'situacao' => $situacao,
            ];

            // Debug - log dos dados
            log_message('debug', 'Dados para inserção: ' . print_r($data, true));

            // Inserir nova ci
            $result = false;
            
            try {
                // Tentar primeiro com o model
                if (method_exists($this->cidadao_model, 'add')) {
                    $result = $this->cidadao_model->add('cidadao', $data);
                } else {
                    // Se não tiver método add, usar insert direto
                    $result = $this->db->insert('cidadao', $data);
                }
                
            } catch (Exception $e) {
                log_message('error', 'Erro ao inserir cidadão: ' . $e->getMessage());
                $result = false;
            }

            if ($result) {
                $this->session->set_flashdata('success', 'Cidadão adicionado com sucesso!');
                log_info('Adicionou um novo cidadão');
                redirect(site_url('Cidadao/gerenciar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro ao salvar os dados.</p></div>';
            }
        }

        $this->data['view'] = 'cidadao/adicionarCidadao';
        return $this->layout();
    }

    public function editar()
    {

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCidadao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cidadão.');
            redirect(base_url());
        }

        $id = $this->uri->segment(3);
        
        if (!$id) {
            $this->session->set_flashdata('error', 'ID do cidadão não informado.');
            redirect(base_url('index.php/Cidadao/gerenciar'));
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        // Configurar regras de validação
        $this->form_validation->set_rules('nome', 'Nome do cidadão', 'required');
        $this->form_validation->set_rules('cpf', 'CPF', 'required|exact_length[14]');
        $this->form_validation->set_rules('cep', 'CEP', 'exact_length[9]');
        // $this->form_validation->set_rules('endereco', 'Endereço', 'required');
        // $this->form_validation->set_rules('situacao', 'Situação', 'required');

        // Configurar mensagens de erro personalizadas
        $this->form_validation->set_message('required', 'O campo %s é obrigatório.');
        $this->form_validation->set_message('exact_length', 'O campo %s deve conter exatamente {param} caracteres.');

        // Recuperar os dados do cidadão usando método mais genérico
        try {
            // Primeiro, tentar com WHERE string
            $this->data['result'] = $this->cidadao_model->get('cidadao', '*', 'idCidadao = ' . (int)$id, 1);

            // Se não retornou dados, tentar com id como segundo parâmetro
            if (!$this->data['result'] || empty($this->data['result'])) {
                $this->data['result'] = $this->cidadao_model->get('cidadao', '*', '', 1, $id);
            }
        } catch (Exception $e) {
            // Se der erro, usar query direta
            $this->data['result'] = $this->db->get_where('cidadao', ['idCidadao' => $id])->row();
        }
        
        if (!$this->data['result'] || empty($this->data['result'])) {
            $this->session->set_flashdata('error', 'Cidadão não encontrado.');
            redirect(base_url('index.php/Cidadao/gerenciar'));
        }

        // Se retornou array, pegar o primeiro item
        if (is_array($this->data['result'])) {
            $this->data['result'] = $this->data['result'][0];
        }

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $nome = $this->input->post('nome');
            $cpf = $this->input->post('cpf');
            $cns = $this->input->post('cns');
            $telefone = $this->input->post('telefone');
            $data_nasc = $this->input->post('data_nasc');
            $sexo = $this->input->post('sexo');
            $filiacao = $this->input->post('filiacao');
            $endereco = $this->input->post('endereco');
            $numero = $this->input->post('numero');
            $cep = $this->input->post('cep');
            $bairro = $this->input->post('bairro');
            $cidade = $this->input->post('cidade');
            $uf = $this->input->post('uf');
            $situacao = $this->input->post('situacao');

            $data = [
                'nome' => $nome,
                'cpf' => $cpf,
                'cns' => $cns,
                'telefone' => $telefone,
                'data_nasc' => $data_nasc,
                'sexo' => $sexo,
                'filiacao' => $filiacao,
                'endereco' => $endereco,
                'numero' => $numero,
                'cep' => $cep,
                'bairro' => $bairro,
                'cidade' => $cidade,
                'uf' => $uf,
                'situacao' => $situacao,
            ];

            // Debug - log dos dados
            log_message('debug', 'Dados para update: ' . print_r($data, true));
            log_message('debug', 'ID: ' . $id);

            // Usar método mais direto para atualização
            $result = false;
            
            try {
                // Tentar primeiro com o model
                if (method_exists($this->cidadao_model, 'edit')) {
                    $result = $this->cidadao_model->edit('cidadao', $data, 'idCidadao', $id);
                }
                
                // Se não funcionou, usar query direta
                if (!$result) {
                    $this->db->where('idCidadao', $id);
                    $result = $this->db->update('cidadao', $data);
                }
                
            } catch (Exception $e) {
                log_message('error', 'Erro ao atualizar cidadão: ' . $e->getMessage());
                $result = false;
            }

            if ($result) {
                $this->session->set_flashdata('success', 'Cidadão editado com sucesso!');
                log_info('Editou um cidadão');
                redirect(site_url('Cidadao/gerenciar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro ao salvar os dados.</p></div>';
            }
        }
        $this->data['view'] = 'cidadao/editarCidadao';
        return $this->layout();

    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dCidadao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir cidadãos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir cidadão.');
            redirect(site_url('cidadao/gerenciar/'));
        }

        $this->cidadao_model->delete('cidadao', 'idCidadao', $id);

        log_info('Removeu um cidadão. ID: ' . $id);

        $this->session->set_flashdata('success', 'Cidadão excluído com sucesso!');
        redirect(site_url('cidadao/gerenciar/'));
    }

}