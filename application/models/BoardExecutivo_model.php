<?php
class BoardExecutivo_model extends CI_Model
{
    private $executivos = [
        'HV' => ['nome' => 'Helena Vasconcelos', 'cargo' => 'CFO', 'cor' => '#a855f7', 'icone' => 'fa-compass'],
        'BT' => ['nome' => 'Bruno Tavares',      'cargo' => 'COO', 'cor' => '#3b82f6', 'icone' => 'fa-microchip'],
        'PL' => ['nome' => 'Patrícia Lemos',     'cargo' => 'CMO', 'cor' => '#22c55e', 'icone' => 'fa-chart-line'],
        'DR' => ['nome' => 'Daniel Ribeiro',     'cargo' => 'CHRO', 'cor' => '#f59e0b', 'icone' => 'fa-shield'],
        'RM' => ['nome' => 'Ricardo Monteiro',   'cargo' => 'CEO', 'cor' => '#ec4899', 'icone' => 'fa-crown'],
    ];

    public function getExecutivos()
    {
        return $this->executivos;
    }

    public function getMetricas()
    {
        $this->load->model('VigiaIa_model');
        $ind = $this->VigiaIa_model->getIndicadores(30);
        $topPaises = $this->VigiaIa_model->getTopPaises(30, 5);
        $alertas = $this->VigiaIa_model->getAlertasCriticos(5);
        $ipsFalhas = $this->VigiaIa_model->getIpsMaisFalhas(30, 5);

        $paisesStr = '';
        foreach ($topPaises as $i => $p) {
            if ($i > 0) $paisesStr .= ', ';
            $paisesStr .= $p->pais . ' (' . $p->total . ')';
        }

        $alertasStr = '';
        foreach ($alertas as $i => $a) {
            if ($i > 0) $alertasStr .= '; ';
            $alertasStr .= $a->titulo;
        }

        return [
            'tentativas_bloqueadas' => $ind->tentativas_bloqueadas ?? 0,
            'novos_cadastros' => $ind->novos_cadastros ?? 0,
            'acessos_suspeitos' => $ind->acessos_suspeitos ?? 0,
            'paises_que_acessaram' => $ind->paises_que_acessaram ?? 0,
            'topPaises' => $paisesStr,
            'alertas' => $alertasStr,
            'totalAlertas' => count($alertas),
        ];
    }

    public function gerarDeliberacao($pauta, $criadoPor)
    {
        $metricas = $this->getMetricas();

        list($falas, $decisaoFinal) = $this->gerarFalas($pauta, $metricas);

        $this->db->insert('board_deliberacoes', [
            'pauta' => $pauta,
            'rodada_atual' => 3,
            'status' => 'concluido',
            'decisao_final' => $decisaoFinal,
            'total_falas' => count($falas),
            'criado_por' => $criadoPor,
            'finalizada_em' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $deliberacaoId = $this->db->insert_id();

        foreach ($falas as $f) {
            $this->db->insert('board_falas', [
                'deliberacao_id' => $deliberacaoId,
                'executivo_sigla' => $f['sigla'],
                'rodada' => $f['rodada'],
                'fala' => $f['fala'],
                'created_at' => $f['created_at'],
            ]);
        }

        return $deliberacaoId;
    }

    public function getDeliberacao($id)
    {
        $d = $this->db->where('id', $id)->get('board_deliberacoes')->row();
        if (!$d) return null;

        $falas = $this->db->where('deliberacao_id', $id)->order_by('created_at', 'ASC')->get('board_falas')->result();

        return ['deliberacao' => $d, 'falas' => $falas];
    }

    public function getHistorico($limite = 10)
    {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limite);
        return $this->db->get('board_deliberacoes')->result();
    }

    public function getFalas($deliberacaoId)
    {
        $this->db->where('deliberacao_id', $deliberacaoId);
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get('board_falas')->result();
    }

    private function gerarFalas($pauta, $metricas)
    {
        $falas = [];
        $t = time();

        $tentativas = $metricas['tentativas_bloqueadas'];
        $suspeitos = $metricas['acessos_suspeitos'];
        $paises = $metricas['paises_que_acessaram'];
        $paisesStr = $metricas['topPaises'];
        $alertasStr = $metricas['alertas'];

        $ordem = ['HV', 'BT', 'PL', 'DR', 'RM'];

        // RODADA 1 — Parecer inicial
        $pareceres = [
            'HV' => function () use ($pauta, $tentativas, $suspeitos) {
                $opinioes = [
                    "Com $tentativas tentativas bloqueadas e $suspeitos acessos suspeitos, nossa estratégia de expansão precisa considerar a segurança como pilar. Sugiro que antes de abrir novas frentes, consolidemos a proteção dos ativos atuais com bloqueios automatizados.",
                    "Analisando o cenário — $tentativas bloqueios no período — entendo que investir em segurança hoje viabiliza o crescimento amanhã. Minha visão é priorizarmos automação de bloqueios como base para expansão segura.",
                    "Cada tentativa de invasão é um risco à nossa reputação no mercado. Proponho que aloquemos parte do orçamento de expansão para segurança ofensiva — isso nos diferencia como uma empresa que cresce com responsabilidade.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'BT' => function () use ($pauta, $suspeitos, $tentativas) {
                $opinioes = [
                    "Do ponto de vista tecnológico, os $suspeitos eventos suspeitos são um sinal claro de que nossa autenticação precisa evoluir. Defendo a implementação de MFA combinada com análise comportamental baseada em IA para reduzir falsos positivos.",
                    "Com $tentativas tentativas rejeitadas e $suspeitos alerts manuais, nossa equipe de tecnologia está sobrecarregada. Minha proposta: automação inteligente dos bloqueios + MFA como camada adicional, com rollout em 30 dias.",
                    "Tecnicamente, o mais eficaz é implementar um web application firewall (WAF) com regras dinâmicas e MFA. Isso reduziria drasticamente os $suspeitos acessos suspeitos e liberaria a equipe para inovação.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'PL' => function () use ($pauta, $paises, $paisesStr, $tentativas) {
                $opinioes = [
                    "Operacionalmente, a variação de $paises países acessando ($paisesStr) exige processos robustos. Já com $tentativas tentativas bloqueadas, precisamos de um fluxo claro: bloqueio automático, notificação ao usuário e canal de recuperação simples.",
                    "Meu foco é garantir que a operação não pare. $paises países distintos acessando ($paisesStr) significa complexidade operacional. Proponho implementarmos triagem automática com regras por país e horário.",
                    "A operação já sente o impacto: $tentativas tentativas de acesso inválido geraram retrabalho. Precisamos de processos definidos — bloqueio, análise, liberação — com SLA claro para cada etapa.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'DR' => function () use ($pauta, $metricas, $alertasStr) {
                $opinioes = [
                    "Riscos à vista. Com alertas como: $alertasStr, nossa exposição é real. Defendo uma abordagem de compliance preventivo — bloquear IPs suspeitos é obrigação regulatória, não opção. MFA deve ser implantado com urgência.",
                    "Do ponto de vista de riscos, cada tentativa de invasão não bloqueada representa passivo. Precisamos de política clara de segurança da informação, aprovada pelo conselho, com responsabilidades definidas e auditoria periódica.",
                    "Minha avaliação de risco é crítica. A frequência de acessos suspeitos exige ação imediata. Sugiro criação de comitê de segurança com reporte direto ao board e metas trimestrais de redução de incidentes.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'RM' => function () use ($pauta, $tentativas, $suspeitos, $alertasStr) {
                $opinioes = [
                    "Excelentes contribuições, conselho. Com $tentativas tentativas bloqueadas e $suspeitos eventos suspeitos ($alertasStr), é evidente que precisamos agir. A pergunta que coloco: devemos priorizar bloqueios automatizados ou MFA? Vou ouvir as réplicas antes de decidir.",
                    "Os números são claros: $tentativas bloqueios, $suspeitos eventos anômalos. Minha inclinação inicial é por bloqueios automáticos imediatos, mas quero ouvir a visão de cada um antes de bater o martelo.",
                    "Este conselho foi convocado para decidir o futuro da nossa segurança digital. Com $suspeitos alertas em aberto e $tentativas tentativas rejeitadas, não podemos adiar. Decidirei após ouvir as réplicas dos pares.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
        ];

        foreach ($ordem as $sigla) {
            $t += 2;
            $falas[] = [
                'sigla' => $sigla,
                'rodada' => 1,
                'fala' => $pareceres[$sigla](),
                'created_at' => date('Y-m-d H:i:s', $t),
            ];
        }

        // RODADA 2 — Réplica
        $replicas = [
            'HV' => function () use ($metricas) {
                $opinioes = [
                    "Após ouvir os pareceres, reforço que a estratégia de crescimento precisa de segurança sólida. Concordo com o Bruno (BT) que MFA é o caminho, e com a Paula (PL) que a operação não pode parar. Sugiro implementação em fases com comunicação clara.",
                    "Boa ponderação do Diego sobre riscos. Minha visão: usemos os dados de $paises países acessando para criar zonas de confiança. Países de alto risco ganham bloqueio automático; os demais, MFA progressivo. Isso equilibra expansão e proteção.",
                    "Concordo com a abordagem do BT em priorizar tecnologia, mas alinhada à estratégia: primeiro bloqueios automáticos (rápido, barato, impacto alto), depois MFA. Isso protege nossa expansão sem travá-la.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'BT' => function () use ($metricas) {
                $opinioes = [
                    "Reforço: temos capacidade técnica para implementar MFA em 15 dias, não 30. Podemos fazer um piloto para a equipe interna e expandir. A tecnologia está madura — o desafio é gestão da mudança, não técnica.",
                    "A Paula trouxe um ponto crucial sobre operação. Minha proposta técnica: WAF + MFA em paralelo. WAF bloqueia os ataques conhecidos automaticamente; MFA protege contra credenciais vazadas. As duas frentes são complementares, não concorrentes.",
                    "Tecnicamente, não vejo conflito entre bloqueios e MFA. Podemos fazer ambos simultaneamente: WAF em 7 dias, MFA em 30. O investimento é baixo comparado ao risco de $tentativas tentativas no período.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'PL' => function () use ($metricas) {
                $opinioes = [
                    "Concordo com o Bruno que ambas as frentes são viáveis. Minha preocupação é operacional: precisamos de um plano de transição que não interrompa o negócio. Sugiro rollout gradual por unidade de negócio.",
                    "A Helena falou em zonas de confiança — excelente ideia. Operacionalmente, podemos implementar por região: primeiro Brasil (maior base), depois expandir. Isso permite aprendizado e ajustes sem impacto global.",
                    "Minha réplica: priorizar bloqueios automáticos (impacto imediato, baixo risco operacional) e planejar MFA para o próximo trimestre. Não podemos parar a operação enquanto implementamos segurança.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'DR' => function () use ($metricas, $alertasStr) {
                $opinioes = [
                    "A sugestão do BT de WAF + MFA é tecnicamente sólida, mas do ponto de vista de riscos, precisamos de política e governança antes de tecnologia. Sem regras claras, a ferramenta não resolve. Proponho política de segurança aprovada neste board primeiro.",
                    "Alertas como $alertasStr mostram que o risco é imediato. Minha posição: não podemos esperar. Bloqueios automáticos já, MFA o quanto antes, e construímos a governança em paralelo. O risco de não fazer é maior que o de fazer rápido.",
                    "Reforço: toda medida de segurança precisa de rastreabilidade. Seja bloqueio automático ou MFA, precisamos de logs, auditoria e relatórios periódicos para este conselho. Sem métricas, não há governança.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
        ];

        foreach ($ordem as $i => $sigla) {
            if ($sigla === 'RM') continue;
            $t += 2;
            $falas[] = [
                'sigla' => $sigla,
                'rodada' => 2,
                'fala' => $replicas[$sigla](),
                'created_at' => date('Y-m-d H:i:s', $t),
            ];
        }

        // RODADA 3 — Decisão final do CEO
        $t += 3;
        $decisoes = [
            "Após ouvir todos os conselheiros, minha decisão é: **Adotar bloqueios automáticos para IPs suspeitos como medida imediata** — começando hoje. Paralelamente, iniciamos a implementação de MFA para todos os usuários administrativos em até 30 dias, liderada pelo BT. A PL cuidará da transição operacional sem impacto ao negócio. O DR estabelecerá a política de segurança e métricas de auditoria. E a HV comunicará a estratégia aos stakeholders. Que fique registrado: a segurança do nosso ecossistema digital é prioridade estratégica deste conselho.",
            "Decisão final: **Implementar MFA para todos os acessos externos e administrativos em até 45 dias**, com bloqueios automáticos para IPs de países de alto risco. O BT lidera a execução técnica, a PL garante a continuidade operacional, o DR define as políticas de conformidade, e a HV coordena a comunicação. Decisão unânime do conselho.",
            "Consolidando as contribuições: **Fase 1 (imediata)**: bloqueios automáticos para IPs suspeitos + revisão dos alertas críticos (liderado pelo BT). **Fase 2 (30 dias)**: MFA para acessos administrativos (PL e BT). **Fase 3 (60 dias)**: política de segurança formal, auditoria contínua e treinamento obrigatório (DR). A HV apresentará o impacto estratégico na próxima reunião. Decisão tomada por este conselho executivo.",
        ];
        $decisao = $decisoes[array_rand($decisoes)];
        $falas[] = [
            'sigla' => 'RM',
            'rodada' => 3,
            'fala' => $decisao,
            'created_at' => date('Y-m-d H:i:s', $t),
        ];

        return [$falas, $decisao];
    }
}
