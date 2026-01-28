# 📊 Sistema de Dashboard

Sistema web moderno, **seguro, responsivo e escalável**, desenvolvido com foco em  
**autenticação avançada**, **controle de sessões**, **experiência do usuário**  
e **boas práticas de segurança**.

---

## 🧱 Tecnologias Utilizadas

- **Laravel 12+**
- **Livewire 4**
- **Alpine.js**
- **Tailwind CSS 4**
- **PHP 8.3+**
- **MySQL / PostgreSQL**

---

## 📌 Visão Geral

Dashboard simples na aparência, porém **robusto e poderoso**, projetado para
**acelerar o desenvolvimento de aplicações web modernas** com alto nível de segurança.

O sistema fornece uma base sólida para projetos que exigem:
- Autenticação forte
- Controle de sessões
- Histórico de acesso
- Experiência fluida e reativa

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

### 🔁 Recuperação de Senha

#### Redefinição por Link de E-mail

- Fluxo seguro de redefinição de senha
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

## 👤 Autenticação (Interface)

| Login | Verificação 2FA |
|------|----------------|
| ![Tela de Login](./login.png) | ![Tela de Verificação](./verify.png) |

---

## 👤 Perfil do Usuário

![Perfil do Usuário](./page-profile.png)

### ✏️ Edição de Dados Pessoais

O usuário pode editar:

- Nome
- E-mail
- Telefone / WhatsApp
- Foto de perfil (opcional)
- Outras informações básicas

---

### 🔐 Gerenciamento de Senha

- Alteração manual de senha
- **Geração automática de senha segura**
- Confirmação obrigatória da nova senha
- Opção de exibir/ocultar senha

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

## 📦 Funcionalidades Resumidas

| Funcionalidade                                   | Status |
|------------------------------------------------|--------|
| Login Seguro                                    | ✅ |
| Limite de Tentativas de Login                   | ✅ |
| Remember Me                                     | ✅ |
| Verificação em Duas Etapas (E-mail)             | ✅ |
| Recuperação de Senha por E-mail                 | ✅ |
| Controle de Sessões por Dispositivo             | ✅ |
| Encerramento Remoto de Sessões                  | ✅ |
| Dashboard Responsivo                            | ✅ |
| Edição de Perfil                                | ✅ |
| Geração Automática de Senha                     | ✅ |
| Indicador de Força de Senha                     | ✅ |

---

## 🧱 Arquitetura (Visão Geral)

- Backend baseado em **Laravel**
- Componentes reativos com **Livewire**
- Interações leves com **Alpine.js**
- Estilização moderna com **Tailwind CSS**
- Separação clara entre:
  - Autenticação
  - Autorização
  - Sessões
  - Perfil do Usuário

---

## 🚀 Possíveis Evoluções Futuras


- Gestão de permissões e papéis (Roles & Permissions)
- Notificações via SMS ou WhatsApp
- Internacionalização (i18n)
- Monitoramento de login suspeito (IP / dispositivo)

---

## 📄 Licença

Voçê pode baixar e usar quantas vezes quiser, sem limite e sem restrinção.
