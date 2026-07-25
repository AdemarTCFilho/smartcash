<?php
class VigiaIa_model extends CI_Model
{
    private function getIntervalCondition($dias)
    {
        if ($dias !== null && $dias !== '') {
            return "created_at >= DATE_SUB(NOW(), INTERVAL " . intval($dias) . " DAY)";
        }
        return "1=1";
    }

    public function getIndicadores($dias = 7)
    {
        $cond = $this->getIntervalCondition($dias);
        $sql = "SELECT
                    (SELECT COUNT(*) FROM vigia_logs WHERE status='falha' AND $cond) as tentativas_bloqueadas,
                    (SELECT COUNT(*) FROM usuarios WHERE dataCadastro >= DATE_SUB(CURDATE(), INTERVAL " . intval($dias ?? 7) . " DAY)) as novos_cadastros,
                    (SELECT COUNT(*) FROM vigia_eventos WHERE severidade IN ('medio','alto','critico') AND $cond) as acessos_suspeitos,
                    (SELECT COUNT(DISTINCT pais) FROM vigia_logs WHERE pais IS NOT NULL AND pais != '' AND pais != 'Desconhecido' AND $cond) as paises_que_acessaram
                ";
        $q = $this->db->query($sql);
        if (!$q) return (object) ['tentativas_bloqueadas' => 0, 'novos_cadastros' => 0, 'acessos_suspeitos' => 0, 'paises_que_acessaram' => 0];
        return $q->row();
    }

    public function getLoginsPorDia($dias = 7)
    {
        $cond = $this->getIntervalCondition($dias);
        $sql = "SELECT
                    DATE(created_at) as dia,
                    SUM(CASE WHEN status='sucesso' THEN 1 ELSE 0 END) as sucesso,
                    SUM(CASE WHEN status='falha' THEN 1 ELSE 0 END) as falha
                FROM vigia_logs
                WHERE $cond
                GROUP BY DATE(created_at)
                ORDER BY dia ASC";
        $q = $this->db->query($sql);
        if (!$q) return [];
        return $q->result();
    }

    public function getTopPaises($dias = 7, $limite = 5)
    {
        $cond = $this->getIntervalCondition($dias);
        $sql = "SELECT
                    COALESCE(NULLIF(pais,''), 'Desconhecido') as pais,
                    COUNT(*) as total
                FROM vigia_logs
                WHERE $cond
                GROUP BY pais
                ORDER BY total DESC
                LIMIT " . intval($limite);
        $q = $this->db->query($sql);
        if (!$q) return [];
        return $q->result();
    }

    public function getIpsMaisFalhas($dias = 7, $limite = 10)
    {
        $cond = $this->getIntervalCondition($dias);
        $sql = "SELECT
                    ip,
                    COALESCE(NULLIF(pais,''), 'Desconhecido') as pais,
                    COUNT(*) as tentativas
                FROM vigia_logs
                WHERE status='falha' AND $cond
                GROUP BY ip
                ORDER BY tentativas DESC
                LIMIT " . intval($limite);
        $q = $this->db->query($sql);
        if (!$q) return [];
        return $q->result();
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
