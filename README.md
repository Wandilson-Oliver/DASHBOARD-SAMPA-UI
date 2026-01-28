# 📊 Sistema de Dashboard

Sistema administrativo focado em **segurança**, **controle de acesso** e **gestão de usuários**, com arquitetura moderna e reatividade em tempo real.

---

## 🧱 Tecnologias

- **Laravel 12+**
- **Livewire 4**
- **Alpine.js**
- **Tailwind CSS 4**
- **PHP 8.3+**
- **MySQL**

---

## 🔐 Autenticação & Segurança

### Login

- Autenticação por **e-mail e senha**
- Limite de **3 tentativas consecutivas**
- Bloqueio temporário automático após exceder o limite
- Opção **Remember Me**
  - Sessão persistente e segura
  - Token com expiração controlada

---

### Verificação em Duas Etapas (2FA – E-mail)

Fluxo aplicado **após o login válido**:

1. Geração de **código único de 6 dígitos**
2. Envio automático para o e-mail do usuário
3. Validação obrigatória para concluir o login

**Características do código:**
- Tempo de expiração
- Uso único
- Invalidado após confirmação

➡️ Garante segurança adicional mesmo com **Remember Me ativo**.

---

## 👥 Gestão de Usuários

### Redefinição de Senha

- Envio de link por e-mail
- Token:
  - Uso único
  - Expiração configurável
- Validação de força da nova senha

---

### Listagem e Controle de Usuários

- Visualização completa dos usuários
- Histórico de acessos por usuário:
  - IP
  - Navegador
  - Plataforma
- Controle de sessões:
  - Encerramento remoto
  - Proteção contra encerramento da sessão atual

---

## 🧩 Papéis & Permissões (RBAC)

- Sistema baseado em **Papéis (Roles)** e **Permissões**
- Permissões organizadas por **módulo**
- Papéis do sistema (ex: **admin**) são protegidos
- Alterações de papel refletem **imediatamente** na interface
- Comunicação entre componentes via **eventos Livewire**

---

## 🛡️ Regras de Segurança

- Senhas com **hash seguro**
- Tokens protegidos contra reutilização
- Sessões invalidadas em:
  - Logout
  - Alteração de senha
  - Encerramento remoto
- Proteção contra **CSRF**
- Validação no backend e frontend
- Middleware de verificação ativa de sessão

---

## 📦 Funcionalidades

| Funcionalidade                                      | Status |
|---------------------------------------------------|--------|
| Login com 2FA via E-mail                            | ✅ |
| Proteção contra força bruta                        | ✅ |
| Remember Me seguro                                 | ✅ |
| Controle de sessões por dispositivo                | ✅ |
| Encerramento remoto de sessões                     | ✅ |
| Detecção de novo dispositivo                       | ✅ |
| Gerenciamento de papéis e permissões               | ✅ |
| Dashboard reativo e responsivo                     | ✅ |

---

## 🚀 Melhorias Futuras

- Notificações internas
- Notificações via SMS / WhatsApp
- Lista de IPs e dispositivos permitidos
- Auditoria avançada de ações administrativas

---

## 📄 Licença

Uso livre para estudo, projetos pessoais ou comerciais, sem restrições.
