<?php
class VigiaIa_model extends CI_Model
{
    public function getIndicadores($dias = 7)
    {
        $this->db->select("
            (SELECT COUNT(*) FROM vigia_logs WHERE status='falha' AND created_at >= DATE_SUB(NOW(), INTERVAL $dias DAY)) as tentativas_bloqueadas,
            (SELECT COUNT(*) FROM usuarios WHERE dataCadastro >= DATE_SUB(CURDATE(), INTERVAL $dias DAY)) as novos_cadastros,
            (SELECT COUNT(*) FROM vigia_eventos WHERE severidade IN ('medio','alto','critico') AND created_at >= DATE_SUB(NOW(), INTERVAL $dias DAY)) as acessos_suspeitos,
            (SELECT COUNT(DISTINCT pais) FROM vigia_logs WHERE pais IS NOT NULL AND pais != '' AND pais != 'Desconhecido' AND created_at >= DATE_SUB(NOW(), INTERVAL $dias DAY)) as paises_que_acessaram
        ");
        return $this->db->get()->row();
    }

    public function getLoginsPorDia($dias = 7)
    {
        $sql = "SELECT
                    DATE(created_at) as dia,
                    SUM(CASE WHEN status='sucesso' THEN 1 ELSE 0 END) as sucesso,
                    SUM(CASE WHEN status='falha' THEN 1 ELSE 0 END) as falha
                FROM vigia_logs
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(created_at)
                ORDER BY dia ASC";
        return $this->db->query($sql, [$dias])->result();
    }

    public function getTopPaises($dias = 7, $limite = 5)
    {
        $sql = "SELECT
                    COALESCE(NULLIF(pais,''), 'Desconhecido') as pais,
                    COUNT(*) as total
                FROM vigia_logs
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY pais
                ORDER BY total DESC
                LIMIT ?";
        return $this->db->query($sql, [$dias, $limite])->result();
    }

    public function getIpsMaisFalhas($dias = 7, $limite = 10)
    {
        $sql = "SELECT
                    ip,
                    COALESCE(NULLIF(pais,''), 'Desconhecido') as pais,
                    COUNT(*) as tentativas
                FROM vigia_logs
                WHERE status='falha' AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY ip
                ORDER BY tentativas DESC
                LIMIT ?";
        return $this->db->query($sql, [$dias, $limite])->result();
    }

    public function getAlertasCriticos($limite = 20)
    {
        $this->db->select('*');
        $this->db->from('vigia_eventos');
        $this->db->where('resolvido', 0);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limite);
        return $this->db->get()->result();
    }

    public function getUltimosLogins($limite = 20)
    {
        $this->db->select('usuario, email, ip, pais, status, motivo, created_at');
        $this->db->from('vigia_logs');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limite);
        return $this->db->get()->result();
    }

    public function getLogsPeriodo($dataInicio, $dataFim)
    {
        $sql = "SELECT * FROM vigia_logs WHERE DATE(created_at) BETWEEN ? AND ? ORDER BY created_at DESC";
        return $this->db->query($sql, [$dataInicio, $dataFim])->result();
    }

    public function registrarLogin($usuario, $email, $ip, $pais, $status, $motivo = null)
    {
        $ci = &get_instance();
        $user_agent = $ci->input->user_agent();
        if (strlen($user_agent) > 500) $user_agent = substr($user_agent, 0, 500);
        $data = [
            'usuario' => $usuario,
            'email' => $email,
            'ip' => $ip,
            'pais' => $pais,
            'status' => $status,
            'motivo' => $motivo,
            'user_agent' => $user_agent,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('vigia_logs', $data);
    }

    public function getBloqueados()
    {
        $this->db->select('*');
        $this->db->from('vigia_ips_bloqueados');
        $this->db->where('ativo', 1);
        $this->db->order_by('bloqueado_em', 'DESC');
        return $this->db->get()->result();
    }
}
