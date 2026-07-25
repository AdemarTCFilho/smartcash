<?php
class BoardExecutivo extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vBoardExecutivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para acessar o Board Executivo.');
            redirect(base_url());
        }

        $this->load->model('BoardExecutivo_model');
        $this->data['menuBoardExecutivo'] = 'Board Executivo';
    }

    public function index()
    {
        $this->data['view'] = 'boardExecutivo/boardExecutivo';
        return $this->layout();
    }

    public function dadosIniciais()
    {
        $executivos = $this->BoardExecutivo_model->getExecutivos();
        $metricas = $this->BoardExecutivo_model->getMetricas();
        $historico = $this->BoardExecutivo_model->getHistorico(5);

        echo json_encode([
            'executivos' => $executivos,
            'metricas' => $metricas,
            'historico' => $historico,
        ]);
    }

    public function convocar()
    {
        $pauta = $this->input->post('pauta');
        if (!$pauta || trim($pauta) === '') {
            echo json_encode(['erro' => 'Informe a pauta para convocar o board.']);
            return;
        }

        $usuario = $this->session->userdata('nome') ?: 'Sistema';
        $id = $this->BoardExecutivo_model->gerarDeliberacao(trim($pauta), $usuario);

        echo json_encode(['id' => $id]);
    }

    public function status()
    {
        $id = $this->input->get('id');
        if (!$id) {
            echo json_encode(['erro' => 'ID da deliberação não informado.']);
            return;
        }

        $data = $this->BoardExecutivo_model->getDeliberacao($id);
        if (!$data) {
            echo json_encode(['erro' => 'Deliberação não encontrada.']);
            return;
        }

        $executivos = $this->BoardExecutivo_model->getExecutivos();

        echo json_encode([
            'deliberacao' => $data['deliberacao'],
            'falas' => $data['falas'],
            'executivos' => $executivos,
        ]);
    }

    public function exportarCSV()
    {
        $id = $this->input->get('id');
        if (!$id) {
            echo 'ID não informado.';
            return;
        }

        $data = $this->BoardExecutivo_model->getDeliberacao($id);
        if (!$data) {
            echo 'Deliberação não encontrada.';
            return;
        }

        $d = $data['deliberacao'];
        $executivos = $this->BoardExecutivo_model->getExecutivos();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="board_executivo_' . $id . '_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, ['TRANSCRIÇÃO — BOARD EXECUTIVO'], ';');
        fputcsv($output, ['Pauta:', $d->pauta], ';');
        fputcsv($output, ['Data:', date('d/m/Y H:i', strtotime($d->created_at))], ';');
        fputcsv($output, ['Status:', $d->status], ';');
        fputcsv($output, [], ';');
        fputcsv($output, ['Rodada', 'Executivo', 'Cargo', 'Fala', 'Data/Hora'], ';');

        $nomesRodada = [1 => 'Parecer Inicial', 2 => 'Réplica', 3 => 'Decisão Final'];

        foreach ($data['falas'] as $f) {
            $exec = $executivos[$f->executivo_sigla] ?? [];
            fputcsv($output, [
                $nomesRodada[$f->rodada] ?? 'Rodada ' . $f->rodada,
                $f->executivo_sigla . ' - ' . ($exec['nome'] ?? ''),
                $exec['cargo'] ?? '',
                $f->fala,
                date('d/m/Y H:i:s', strtotime($f->created_at)),
            ], ';');
        }

        if ($d->decisao_final) {
            fputcsv($output, [], ';');
            fputcsv($output, ['DECISÃO FINAL:', strip_tags($d->decisao_final)], ';');
        }

        fclose($output);
    }
}
