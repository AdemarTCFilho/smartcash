<div align="center">
  <h1>🚀 SmartCash</h1>
  <p><strong>Sistema de Gestão Financeira e Ordem de Serviço para a Saúde</strong></p>
  <p>
    <img src="https://img.shields.io/badge/PHP-7.3%2B-777BB4?logo=php" alt="PHP">
    <img src="https://img.shields.io/badge/CodeIgniter-3.1.11-orange" alt="CodeIgniter">
    <img src="https://img.shields.io/badge/MySQL-5.7%2B-4479A1?logo=mysql" alt="MySQL">
    <img src="https://img.shields.io/badge/licença-MIT-green" alt="License">
    <img src="https://img.shields.io/badge/versão-4.39.0-blue" alt="Version">
  </p>
</div>

---

## 📋 Sobre o Projeto

O **SmartCash** é um sistema completo de **gestão financeira e ordens de serviço**, originalmente baseado no [Map-OS](https://github.com/RamonSilva20/mapos) e adaptado para atender às necessidades do **setor de saúde brasileiro**.

Com uma interface moderna e módulos integrados, o SmartCash permite gerenciar desde o atendimento ao cidadão/paciente até o controle financeiro completo da sua organização.

---

## ✨ Funcionalidades

### 👥 Gestão de Pessoas
- **Cidadão/Paciente** — Cadastro completo de cidadãos com histórico
- **Profissionais de Saúde** — Gestão de médicos, enfermeiros e demais profissionais
- **Clientes e Fornecedores** — Cadastro com CPF/CNPJ, endereço e contato

### 📅 Agendamentos
- **Agenda de Atendimentos** — Calendário integrado com agendamento de consultas
- **Unidades Solicitantes e Executantes** — Controle de unidades de saúde

### 🔧 Ordens de Serviço
- Ciclo completo: Aberto → Em Andamento → Orçamento → Negociação → Finalizado → Faturado
- Controle de equipamentos e garantias
- Histórico de anotações por OS

### 💰 Financeiro
- **Contas a Pagar e Receber** — Lançamentos com categorização
- **Conciliação Bancária** — Compatibilização de extratos
- **Fluxo de Caixa** — Visão geral das movimentações
- **DRE Gerencial** — Demonstrativo de Resultados
- **Metas Financeiras** — Acompanhamento de metas
- **Projetado vs Realizado** — Comparativo de desempenho
- **Ranking de Receitas e Despesas** — Análise por categoria

### 🏪 Vendas e Estoque
- Controle de vendas com itens
- Gestão de estoque com alerta de estoque mínimo
- Produtos com código de barras, preço de custo e venda

### 💳 Gateways de Pagamento
- **Asaas** — Boleto, link de pagamento e PIX
- **Mercado Pago** — Boleto
- **Gerencianet** — Boleto e link de pagamento

### 🔐 Administração
- Controle de usuários com níveis de permissão granular
- Tema claro/escuro
- Backup do banco de dados
- Envio de e-mails com fila inteligente
- Notificações via WhatsApp
- Log completo de auditoria
- Auto-update via GitHub

---

## 🛠 Stack Tecnológica

| Camada | Tecnologia |
|--------|-----------|
| **Backend** | PHP 7.3+, CodeIgniter 3.1.11 |
| **Banco de Dados** | MySQL 5.7.22+ |
| **Frontend** | jQuery, Bootstrap 3/4, Vuexy Admin |
| **Gráficos** | ApexCharts, Chart.js |
| **Calendário** | FullCalendar |
| **Tabelas** | DataTables |
| **PDF** | mPDF |
| **Pagamentos** | Asaas, Mercado Pago, Gerencianet, PIX |

---

## 🚀 Instalação

### Pré-requisitos

- PHP 7.3 ou superior com extensões: `cURL`, `MySQLi`, `GD`
- MySQL 5.7 ou superior
- [Composer](https://getcomposer.org/)
- Apache com `mod_rewrite` (ou nginx)

### Instalação Convencional (XAMPP/WAMP)

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/smartcash.git

# 2. Acesse o diretório
cd smartcash

# 3. Instale as dependências do Composer
composer install --no-dev

# 4. Acesse via navegador
http://localhost/smartcash/install/
```

O instalador web irá guiar você pelo processo de configuração do banco de dados e criação do usuário administrador.

### Instalação com Docker

```bash
cd docker
docker-compose up -d
```

Isso iniciará os containers de Nginx, PHP-FPM, MySQL e phpMyAdmin.

---

## ⚙️ Configuração

### E-mail (SMTP)

Edite o arquivo `application/config/email.php` com suas credenciais SMTP.

### Gateways de Pagamento

Configure as credenciais em `application/config/payment_gateways.php`.

### Cron Jobs

Para envio automático de e-mails, configure o cron job conforme documentação em `Cronjobs no Windows para enviar os e-emails.md`.

---

## 📄 Licença

Este projeto é licenciado sob a licença **MIT** — veja o arquivo [LICENSE.txt](LICENSE.txt) para mais detalhes.

---

## 🙏 Créditos

Este sistema é um fork customizado do [Map-OS](https://github.com/RamonSilva20/mapos) criado originalmente por **Ramon Silva**. Agradecimentos especiais a todos os contribuidores do projeto original.

---

<div align="center">
  <p>Desenvolvido com ❤️ para o setor de saúde brasileiro</p>
</div>
