<?php
class BoardExecutivo_model extends CI_Model
{
    private $executivos = [
        'CFO' => ['nome' => 'Carlos Mendes', 'cargo' => 'Chief Financial Officer', 'cor' => '#ff6b6b', 'icone' => 'fa-calculator'],
        'COO' => ['nome' => 'Ana Oliveira',  'cargo' => 'Chief Operating Officer', 'cor' => '#4ecdc4', 'icone' => 'fa-cogs'],
        'CMO' => ['nome' => 'Rafael Torres', 'cargo' => 'Chief Marketing Officer',  'cor' => '#45b7d1', 'icone' => 'fa-bullhorn'],
        'CHRO' => ['nome' => 'Juliana Costa','cargo' => 'Chief Human Resources Officer','cor' => '#96ceb4','icone' => 'fa-users'],
        'CEO' => ['nome' => 'Eduardo Alves','cargo' => 'Chief Executive Officer',  'cor' => '#ffeaa7','icone' => 'fa-star'],
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

        $ordem = ['CFO', 'COO', 'CMO', 'CHRO', 'CEO'];

        // RODADA 1 — Parecer inicial
        $pareceres = [
            'CFO' => function () use ($pauta, $tentativas) {
                $opinioes = [
                    "Considerando que tivemos $tentativas tentativas de bloqueio no período, precisamos avaliar o custo-benefício de cada medida. Minha preocupação é com o impacto financeiro. Sugiro priorizarmos soluções com retorno mensurável.",
                    "Analisando os números — $tentativas bloqueios registrados — entendo que investir em segurança é necessário, mas precisamos de um plano com métricas claras de ROI. Minha sugestão é começarmos com bloqueios automáticos que já mostraram eficácia.",
                    "Do ponto de vista financeiro, cada tentativa de invasão representa um risco de perda. Com $tentativas bloqueios no período, o custo da inação supera o investimento em prevenção. Defendo um aporte gradual em ferramentas de segurança.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'COO' => function () use ($pauta, $suspeitos, $tentativas) {
                $opinioes = [
                    "Operacionalmente, os $suspeitos acessos suspeitos indicam que nossos processos de autenticação precisam de revisão. Suporte já reporta sobrecarga com alerts. Precisamos de automação nos bloqueios para desafogar a equipe.",
                    "Com $tentativas tentativas bloqueadas e $suspeitos acessos suspeitos, a operação está no limite. Sugiro implementarmos autenticação multifator como camada adicional — isso reduziria significativamente os alerts manuais.",
                    "Nossa equipe operacional gastou horas significativas analisando $suspeitos eventos suspeitos. Precisamos de um sistema que faça esse filtro automaticamente. MFA + bloqueio automático é o caminho.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'CMO' => function () use ($pauta, $paises, $paisesStr) {
                $opinioes = [
                    "Com $paises países diferentes acessando o sistema, sendo os principais: $paisesStr, precisamos garantir que a experiência do usuário não seja comprometida. Bloqueios muito agressivos podem impactar clientes legítimos.",
                    "A presença de $paises países distintos ($paisesStr) mostra que estamos ganhando visibilidade internacional, mas também atraindo atenção indesejada. Minha preocupação é equilibrar segurança com usabilidade.",
                    "Precisamos comunicar claramente qualquer mudança nos processos de login para não gerar atrito com usuários legítimos. Sugiro campanha interna de conscientização sobre boas práticas de segurança.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'CHRO' => function () use ($pauta, $metricas) {
                $opinioes = [
                    "Precisamos treinar todos os colaboradores para identificar tentativas de phishing e ataques. Tecnologia sozinha não resolve — o fator humano é crítico. Sugiro workshop obrigatório de segurança da informação.",
                    "Com o aumento de acessos suspeitos, nossos colaboradores precisam estar preparados. Proponho um programa de conscientização contínua, com simulações de phishing e treinamento trimestral.",
                    "A segurança começa nas pessoas. Precisamos reforçar a cultura de segurança com políticas claras de senhas, autenticação em dois fatores para todos os sistemas e relatórios periódicos de conformidade.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'CEO' => function () use ($pauta, $tentativas, $suspeitos, $alertasStr) {
                $opinioes = [
                    "Excelentes contribuições. Com $tentativas tentativas bloqueadas e $suspeitos eventos suspeitos, está claro que precisamos agir. A pergunta é: qual a prioridade — bloqueios mais rigorosos ou MFA? Precisamos decidir como conselho.",
                    "Os números ($tentativas bloqueios, $suspeitos eventos) falam por si. Meu entendimento é que ambas as frentes são necessárias, mas precisamos definir qual dará retorno mais rápido. Vou ouvir as réplicas antes de decidir.",
                    "Estou inclinado a priorizar bloqueios automáticos como medida imediata, dado o volume $tentativas de tentativas. Mas quero ouvir as réplicas dos pares antes de bater o martelo.",
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
            'CFO' => function () use ($metricas) {
                $opinioes = [
                    "Após ouvir os colegas, reforço que a análise de custo-benefício precisa ser nossa bússola. Concordo com a Ana (COO) que a automação é prioritária — reduz custos operacionais. E com a Juliana (CHRO) que treinamento é essencial, mas sugiro escalonar em fases.",
                    "Boa ponderação do Rafael sobre experiência do usuário. Minha contraproposta: implementamos MFA apenas para acessos externos primeiro, enquanto mantemos bloqueios automáticos para IPs suspeitos. Isso equilibra segurança e custo.",
                    "Concordo com a abordagem em fases. Primeiro, bloqueios automáticos (custo baixo, impacto alto). Depois, MFA para usuários administrativos. E paralelamente, o programa de treinamento que a Juliana sugeriu. Isso distribui o investimento.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'COO' => function () use ($metricas) {
                $opinioes = [
                    "Reforçando meu ponto: automação é urgente. A equipe de operações está sobrecarregada. Se implementarmos bloqueios automáticos + MFA, liberamos capacidade para outras atividades estratégicas. Concordo com a abordagem do CFO em fases.",
                    "O Rafael trouxe um ponto válido sobre usabilidade. Mas acredito que MFA bem implementado não compromete a experiência — é rápido e indolor. Já vi cases de sucesso em empresas do nosso porte.",
                    "Minha réplica: prioridade máxima é automação dos bloqueios. Depois MFA. Em paralelo, treinamento. Não podemos fazer tudo ao mesmo tempo sem sobrecarregar a operação.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'CMO' => function () use ($metricas) {
                $opinioes = [
                    "Entendo o senso de urgência de todos, mas reforço: qualquer mudança precisa ser comunicada com antecedência aos usuários. Sugiro implementarmos as medidas em ambiente controlado, com período de testes e comunicação clara.",
                    "A Ana tem razão — MFA quando bem feito não atrapalha. Mas precisamos garantir que o processo de recuperação de acesso seja simples. Nada frustra mais o usuário que ficar preso fora do sistema.",
                    "Minha sugestão: comunicado oficial 15 dias antes, tutorial passo a passo, e canal de suporte dedicado durante a transição. Isso minimiza o impacto na percepção dos usuários.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
            'CHRO' => function () use ($metricas) {
                $opinioes = [
                    "Apoio a abordagem faseada do CFO. Meu acréscimo: o treinamento de segurança deve começar antes das mudanças técnicas, não depois. Preparar as pessoas primeiro reduz resistência e acelera a adoção.",
                    "Excelente ponto do Rafael sobre comunicação. Vou alinhar com marketing para produzir materiais didáticos — vídeos curtos, infográficos e FAQ. Educação é a chave para o sucesso de qualquer mudança.",
                    "Complementando: precisamos de métricas de adesão. Quantos colaboradores completaram o treinamento? Quantos ativaram MFA? Sem dados, não sabemos se a estratégia está funcionando.",
                ];
                return $opinioes[array_rand($opinioes)];
            },
        ];

        foreach ($ordem as $i => $sigla) {
            if ($sigla === 'CEO') continue; // CEO não replica, ele decide
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
            "Após ouvir todos os conselheiros, minha decisão é: **Adotar bloqueios automáticos para IPs suspeitos como medida imediata** — começando hoje. Paralelamente, iniciamos a implementação de MFA para todos os usuários administrativos em até 30 dias. O programa de treinamento de segurança será lançado na próxima semana, liderado pelo CHRO em parceria com o CMO para comunicação. Que fique registrado: a segurança do nosso ecossistema digital é prioridade estratégica deste conselho.",
            "Decisão final: **Implementar MFA para todos os acessos externos e administrativos em até 45 dias**, com bloqueios automáticos para IPs de países de alto risco (baseado no ranking que vimos). O COO liderará a automação com apoio do CFO para orçamento. O CMO e CHRO cuidarão da comunicação e treinamento. Esta é uma decisão unânime deste conselho.",
            "Consolidando as contribuições: **Fase 1 (imediata)**: bloqueios automáticos para IPs suspeitos + revisão dos alertas críticos. **Fase 2 (30 dias)**: MFA para acessos administrativos. **Fase 3 (60 dias)**: MFA para todos os usuários + treinamento obrigatório. O CFO apresentará o impacto financeiro na próxima reunião. Decisão tomada por este conselho executivo.",
        ];
        $decisao = $decisoes[array_rand($decisoes)];
        $falas[] = [
            'sigla' => 'CEO',
            'rodada' => 3,
            'fala' => $decisao,
            'created_at' => date('Y-m-d H:i:s', $t),
        ];

        return [$falas, $decisao];
    }
}
