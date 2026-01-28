# 📊 Sistema de Dashboard

Dashboard, escalavel, seguro e responsivo.

---

## 🧱 Tecnologias Utilizadas

- **Laravel 12+**
- **Livewire 4**
- **Alpine.js**
- **Tailwind CSS 4**
- **PHP 8.3+**
- **MySQL**

---

## 🔐 Autenticação & Segurança

### 🔑 Login

- Autenticação via **e-mail e senha**
- Limite de **até 3 tentativas consecutivas de login**
- Bloqueio temporário após exceder o limite
- Proteção contra **ataques de força bruta**
- Opção **Remember Me**
  - Mantém o usuário autenticado com segurança
  - Token persistente com expiração configurável

---

### 🔐 Verificação em Duas Etapas (2FA via E-mail)

Após o login inicial:

1. Geração automática de um **código de verificação único**
2. Envio do código para o **e-mail do usuário**
3. Validação obrigatória para concluir o login
4. O código:
   - Possui tempo de expiração
   - É de uso único
   - É invalidado após a confirmação

Esse fluxo garante uma **camada extra de segurança**, mesmo quando o usuário utiliza
o recurso **Remember Me**.

---

## 👥 Gestão de Usuários

### Redefinição de e-mail por Link

- Link enviado para o e-mail cadastrado
- Token de redefinição:
  - Uso único
  - Tempo de expiração configurável
- Validação da força da nova senha

---

### 📜 Listagem de Usuários

- Visualização de usuários cadastrados
- **Histórico de acessos por usuário**
- Identificação de dispositivos conectados
- Possibilidade de **encerrar sessões em outros dispositivos**
- Proteção contra encerramento da sessão atual

---

## 👤 Perfil do Usuário

![Perfil do Usuário](./page-profile.png)

---

### 💪 Validação de Força de Senha

A senha é validada automaticamente com base em:

- Comprimento mínimo
- Letras maiúsculas e minúsculas
- Números
- Caracteres especiais

Indicador visual de força:
- Fraca
- Média
- Forte

---

## ⚙️ Regras de Segurança

- Senhas armazenadas com **hash seguro**
- Tokens protegidos contra reutilização
- Sessões invalidadas em:
  - Logout manual
  - Encerramento remoto de sessão
  - Alteração de senha
- Proteção contra **CSRF**
- Validação de dados no **backend e frontend**
- Controle de sessão com verificação ativa no middleware

---

## 📦 Funcionalidades Principais

| Funcionalidade                                      | Status |
|---------------------------------------------------|--------|
| Autenticação Segura com 2FA (E-mail)               | ✅ |
| Proteção contra Força Bruta (limite de tentativas) | ✅ |
| Controle de Sessões por Dispositivo                | ✅ |
| Encerramento Remoto de Sessões                     | ✅ |
| Detecção de Login em Novo Dispositivo              | ✅ |
| Dashboard Responsivo e Reativo                     | ✅ |
| Gerenciamento de Perfil do Usuário                 | ✅ |


---

## 🚀 Melhorias Futuras


- Gestão de permissões e papéis (Roles & Permissions)
- Notificações via SMS ou WhatsApp
- Notificações Internas
- Lista de Ips e Dispositivos permitidos

---

## 📄 Licença

Voçê pode baixar e usar quantas vezes quiser, sem limite e sem restrinção.
