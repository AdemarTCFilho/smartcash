<?php
class VigiaIa extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vVigiaIa')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para acessar o Vigia IA.');
            redirect(base_url());
        }

        $this->load->model('VigiaIa_model');
        $this->data['menuVigiaIa'] = 'Vigia IA';
    }

    public function index()
    {
        $this->data['view'] = 'vigiaIa/vigiaIa';
        return $this->layout();
    }

    public function getDados()
    {
        $dias = $this->input->get('dias') ? intval($this->input->get('dias')) : 7;

        $data_inicio = $this->input->get('data_inicio');
        $data_fim = $this->input->get('data_fim');
        if ($data_inicio && $data_fim) {
            $dias = null;
        }

        $indicadores = $this->VigiaIa_model->getIndicadores($dias);
        $loginsPorDia = $this->VigiaIa_model->getLoginsPorDia($dias);
        $topPaises = $this->VigiaIa_model->getTopPaises($dias);
        $ipsFalhas = $this->VigiaIa_model->getIpsMaisFalhas($dias);
        $alertas = $this->VigiaIa_model->getAlertasCriticos();
        $ultimosLogins = $this->VigiaIa_model->getUltimosLogins();
        $bloqueados = $this->VigiaIa_model->getBloqueados();

        $htmlAlertas = '';
        foreach ($alertas as $a) {
            $severidadeClass = strtolower($a->severidade);
            $icone = $a->severidade === 'critico' ? 'fa-exclamation-triangle' : ($a->severidade === 'alto' ? 'fa-exclamation-circle' : 'fa-info-circle');
            $htmlAlertas .= '<div class="alerta-item severity-' . $severidadeClass . '">
                <div class="alerta-icon"><i class="fa ' . $icone . '"></i></div>
                <div class="alerta-body">
                    <div class="alerta-titulo">' . htmlspecialchars($a->titulo) . '</div>
                    <div class="alerta-desc">' . htmlspecialchars($a->descricao ?: '') . '</div>
                    <div class="alerta-meta">' . htmlspecialchars($a->usuario ?: 'Sistema') . ' — ' . date('d/m/Y H:i', strtotime($a->created_at)) . ' — ' . htmlspecialchars($a->ip ?: '-') . '</div>
                </div>
                <div class="alerta-sev"><span class="sev-badge ' . $severidadeClass . '">' . strtoupper($a->severidade) . '</span></div>
            </div>';
        }
        if (!$alertas) {
            $htmlAlertas = '<div class="alerta-item" style="color:var(--muted);text-align:center;padding:20px"><i class="fa fa-check-circle" style="color:var(--green);font-size:24px"></i><br>Nenhum alerta crítico no momento</div>';
        }

        $htmlUltimosLogins = '';
        foreach ($ultimosLogins as $l) {
            $statusClass = $l->status === 'sucesso' ? 'sucesso' : 'falha';
            $statusIcon = $l->status === 'sucesso' ? 'fa-check-circle' : 'fa-times-circle';
            $htmlUltimosLogins .= '<div class="login-item">
                <div class="login-status ' . $statusClass . '"><i class="fa ' . $statusIcon . '"></i></div>
                <div class="login-info">
                    <div class="login-user">' . htmlspecialchars($l->usuario ?: 'Anônimo') . '</div>
                    <div class="login-meta">' . htmlspecialchars($l->ip ?: '-') . ' — ' . htmlspecialchars($l->pais ?: 'Desconhecido') . ' — ' . date('d/m/Y H:i', strtotime($l->created_at)) . '</div>
                </div>
            </div>';
        }

        $htmlIpsFalhas = '';
        foreach ($ipsFalhas as $ip) {
            $htmlIpsFalhas .= '<tr><td>' . htmlspecialchars($ip->ip) . '</td><td>' . htmlspecialchars($ip->pais) . '</td><td><span class="badge-falha">' . $ip->tentativas . '</span></td></tr>';
        }
        if (!$ipsFalhas) {
            $htmlIpsFalhas = '<tr><td colspan="3" style="color:var(--muted);text-align:center">Nenhuma falha registrada no período</td></tr>';
        }

        $htmlTopPaises = '';
        foreach ($topPaises as $p) {
            $htmlTopPaises .= '<tr><td><span class="pais-flag">' . $this->getFlagEmoji($p->pais) . '</span> ' . htmlspecialchars($p->pais) . '</td><td><strong>' . $p->total . '</strong> acessos</td></tr>';
        }

        $htmlBloqueados = '';
        foreach ($bloqueados as $b) {
            $htmlBloqueados .= '<tr><td>' . htmlspecialchars($b->ip) . '</td><td>' . htmlspecialchars($b->pais ?: '-') . '</td><td>' . htmlspecialchars($b->motivo ?: '-') . '</td><td>' . date('d/m/Y H:i', strtotime($b->bloqueado_em)) . '</td></tr>';
        }
        if (!$bloqueados) {
            $htmlBloqueados = '<tr><td colspan="4" style="color:var(--muted);text-align:center">Nenhum IP bloqueado</td></tr>';
        }

        echo json_encode([
            'indicadores' => $indicadores,
            'loginsPorDia' => $loginsPorDia,
            'htmlAlertas' => $htmlAlertas,
            'htmlUltimosLogins' => $htmlUltimosLogins,
            'htmlIpsFalhas' => $htmlIpsFalhas,
            'htmlTopPaises' => $htmlTopPaises,
            'htmlBloqueados' => $htmlBloqueados,
        ]);
    }

    public function exportarCSV()
    {
        $dias = $this->input->get('dias') ? intval($this->input->get('dias')) : 7;
        $data_inicio = $this->input->get('data_inicio');
        $data_fim = $this->input->get('data_fim');

        if ($data_inicio && $data_fim) {
            $logs = $this->VigiaIa_model->getLogsPeriodo($data_inicio, $data_fim);
        } else {
            $logs = $this->db->query("SELECT * FROM vigia_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) ORDER BY created_at DESC", [$dias])->result();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="vigia_ia_relatorio_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['ID', 'Usuário', 'Email', 'IP', 'País', 'Status', 'Motivo', 'Data/Hora'], ';');
        foreach ($logs as $l) {
            fputcsv($output, [$l->id, $l->usuario, $l->email, $l->ip, $l->pais, $l->status, $l->motivo, $l->created_at], ';');
        }
        fclose($output);
    }

    private function getFlagEmoji($pais)
    {
        $mapa = [
            'Brasil' => "\xF0\x9F\x87\xA7\xF0\x9F\x87\xB7",
            'Estados Unidos' => "\xF0\x9F\x87\xBA\xF0\x9F\x87\xB8",
            'Rússia' => "\xF0\x9F\x87\xB7\xF0\x9F\x87\xBA",
            'China' => "\xF0\x9F\x87\xA8\xF0\x9F\x87\xB3",
            'Alemanha' => "\xF0\x9F\x87\xA9\xF0\x9F\x87\xAA",
            'Argentina' => "\xF0\x9F\x87\xA6\xF0\x9F\x87\xB7",
            'França' => "\xF0\x9F\x87\xAB\xF0\x9F\x87\xB7",
            'Portugal' => "\xF0\x9F\x87\xB5\xF0\x9F\x87\xB9",
            'Canadá' => "\xF0\x9F\x87\xA8\xF0\x9F\x87\xA6",
            'Reino Unido' => "\xF0\x9F\x87\xAC\xF0\x9F\x87\xA7",
        ];
        return $mapa[$pais] ?? "\xF0\x9F\x8C\x8D";
    }
}
