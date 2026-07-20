# Implementação Select2 com AJAX - Códigos para Adicionar

## ✅ JÁ IMPLEMENTADO
- ✅ Arquivo `todays.php` já foi modificado com sucesso

## 📋 CÓDIGOS PARA ADICIONAR NO SERVIDOR

### 1️⃣ NO ARQUIVO: `patient_model.php`
**Localização:** `application/modules/patient/models/patient_model.php`

**Adicione ANTES do último `}`** (após a função `updateIonUser`):

```php
    function getPatientsPaginated($search = '', $page = 1, $limit = 30) {
        $offset = ($page - 1) * $limit;
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('sus', $search);
            $this->db->or_like('name_mother', $search);
            $this->db->or_like('cpf', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('name', 'asc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('patient');
        
        return $query->result();
    }

    function countPatients($search = '') {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('sus', $search);
            $this->db->or_like('name_mother', $search);
            $this->db->or_like('cpf', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results('patient');
    }
```

---

### 2️⃣ NO ARQUIVO: `appointment.php` (Controller)
**Localização:** `application/modules/appointment/controllers/appointment.php`

**Adicione ANTES do último `}`** (após a função `checkNewAppointments`):

```php
    function getPatientsByAjax() {
        $search = $this->input->get('search');
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $limit = 30;
        
        $patients = $this->patient_model->getPatientsPaginated($search, $page, $limit);
        $total = $this->patient_model->countPatients($search);
        
        $results = array();
        
        // Sempre adicionar a opção "add_new" no início quando não há busca
        if (empty($search) && $page == 1) {
            $results[] = array(
                'id' => 'add_new',
                'text' => 'ADICIONAR NOVO PACIENTE'
            );
        }
        
        foreach ($patients as $patient) {
            $results[] = array(
                'id' => $patient->id,
                'text' => $patient->name . 
                         ' | SUS: ' . ($patient->sus ? $patient->sus : '') . 
                         ' | FILIAÇÃO: ' . ($patient->name_mother ? $patient->name_mother : '') . 
                         ' | SEXO: ' . ($patient->sex ? $patient->sex : '') . 
                         ' | DATA NASC.: ' . ($patient->birthdate ? $patient->birthdate : '') . 
                         ' | EST. CIVIL: ' . ($patient->estado_civil ? $patient->estado_civil : '') . 
                         ' | ENDEREÇO: ' . ($patient->address ? $patient->address : '') . 
                         ' | PROFISSÃO: ' . ($patient->profissao ? $patient->profissao : '')
            );
        }
        
        $response = array(
            'results' => $results,
            'pagination' => array(
                'more' => ($page * $limit) < $total
            )
        );
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
```

---

## 🎯 COMO FUNCIONA

### Antes (Problema):
- ❌ Carregava TODOS os pacientes de uma vez (milhares de registros)
- ❌ Página travava ao carregar
- ❌ Lentidão extrema no navegador

### Depois (Solução):
- ✅ Carrega apenas 30 pacientes por vez
- ✅ Busca em tempo real enquanto digita
- ✅ Paginação automática (scroll infinito)
- ✅ Busca por: Nome, SUS, Filiação, CPF
- ✅ Página carrega instantaneamente

---

## 📝 PASSOS PARA FINALIZAR

1. **Abra o arquivo** `patient_model.php` no servidor
2. **Cole o código do item 1** antes do último `}`
3. **Abra o arquivo** `appointment.php` (controller) no servidor
4. **Cole o código do item 2** antes do último `}`
5. **Salve os arquivos**
6. **Teste no navegador**

---

## 🧪 COMO TESTAR

1. Limpe o cache do navegador (Ctrl + Shift + Del)
2. Acesse a página de agendamentos
3. Clique no select de pacientes
4. Digite qualquer letra para buscar
5. Role para baixo para ver a paginação
6. Verifique se está rápido! ⚡

---

## 🔧 CONFIGURAÇÕES AJUSTÁVEIS

No código JavaScript (já implementado em `todays.php`):

```javascript
limit = 30;  // Quantidade de registros por página (pode alterar para 50, 100, etc)
delay: 250   // Delay em ms antes de buscar (pode ajustar)
```

---

## ❗ IMPORTANTE

- O arquivo `todays.php` **JÁ FOI MODIFICADO** ✅
- Você só precisa adicionar os códigos no **MODEL** e no **CONTROLLER**
- Certifique-se de adicionar os códigos nos lugares corretos (antes do último `}`)

---

## 🆘 SUPORTE

Se houver algum erro:

1. Verifique se os códigos foram colados corretamente
2. Verifique se não há erros de sintaxe PHP
3. Verifique o console do navegador (F12) para ver erros JavaScript
4. Verifique se a URL está correta: `appointment/getPatientsByAjax`

---

**Data da implementação:** 12/11/2025
**Arquivo modificado automaticamente:** `todays.php` ✅
