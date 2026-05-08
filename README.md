# O Cofre Digital - Mini API Enterprise em PHP

## Visão Geral

"O Cofre Digital" é um sistema de mini API desenvolvido em PHP com foco em **Engenharia de Software profissional** e **Security by Design**. Ele foi projetado para armazenar segredos de usuários de forma segura, oferecendo uma API RESTful para autenticação e gerenciamento de segredos. O projeto segue uma arquitetura MVC leve, com backend dominante em PHP e um frontend minimalista para consumo da API.

Este sistema é ideal para um contexto acadêmico, preparado para auditorias de **Blue Team** (defesa da arquitetura) e **Red Team** (exploração de falhas), garantindo que seja seguro por padrão, previsível, auditável, bem estruturado e fácil de explicar tecnicamente.

## Funcionalidades

- **Autenticação de Usuários**: Registro, login e logout seguros utilizando `password_hash` e `password_verify`.
- **Gerenciamento de Segredos**: Criação, listagem e visualização de segredos, com isolamento total por `user_id`.
- **API RESTful**: Endpoints claros e padronizados para todas as operações.
- **Security by Design**: Implementação de PDO prepared statements para prevenção de SQL Injection, validação de entrada centralizada, autenticação via sessão PHP e não exposição de erros do PHP ao frontend.
- **Frontend Minimalista**: Interface simples e funcional em dark mode para interação com a API.

## Stack Tecnológica

- **Backend**: PHP 8+ puro
- **Banco de Dados**: MySQL (com PDO obrigatório)
- **Frontend**: HTML5, CSS3 simples, JavaScript mínimo (fetch API)
- **Ambiente de Desenvolvimento**: XAMPP compatível
- **Comunicação**: JSON como padrão

## Arquitetura do Projeto

O projeto segue uma estrutura MVC leve, organizada da seguinte forma:

```
cofre-digital/
│
├── api/                     # Controllers (endpoints da API)
│   ├── AuthController.php   # Lógica de autenticação (registro, login, logout)
│   ├── SecretController.php # Lógica de gerenciamento de segredos
│
├── core/                    # Núcleo do sistema
│   ├── Database.php         # Conexão Singleton com o banco de dados (PDO)
│   ├── Response.php         # Padronização de respostas JSON
│   ├── AuthMiddleware.php   # Middleware para verificação de autenticação
│   ├── Security.php         # Funções de sanitização e validação de entrada
│
├── models/                  # Modelos de dados
│   ├── User.php             # Lógica de negócios para usuários
│   ├── Secret.php           # Lógica de negócios para segredos
│
├── config/                  # Configurações do sistema
│   └── config.php           # Variáveis de ambiente e configurações globais
│
├── database/                # Scripts de banco de dados
│   └── schema.sql           # Esquema de criação do banco de dados e tabelas
│
├── public/                  # Arquivos públicos (frontend)
│   ├── index.html           # Página de login e registro
│   ├── dashboard.html       # Dashboard do usuário para gerenciar segredos
│   ├── app.js               # Lógica JavaScript do frontend
│   ├── style.css            # Estilos CSS (dark mode)
```

## Como Configurar e Rodar o Projeto

### Pré-requisitos

- Servidor web com PHP 8+ (XAMPP, WAMP, MAMP ou similar)
- MySQL

### Passos para Instalação

1.  **Clone ou Baixe o Projeto**: Copie a pasta `cofre-digital` para o diretório `htdocs` do seu XAMPP (ou equivalente).

2.  **Configurar Banco de Dados**:
    a.  Abra o phpMyAdmin (ou seu cliente MySQL preferido).
    b.  Crie um novo banco de dados chamado `cofre_digital`.
    c.  Importe o arquivo `database/schema.sql` para criar as tabelas `users` e `secrets`.

3.  **Configurar `config.php`**:
    a.  O arquivo `config/config.php` já está configurado com as credenciais padrão do XAMPP (`DB_USER: root`, `DB_PASS: `). Se suas credenciais MySQL forem diferentes, ajuste-as neste arquivo.

4.  **Acessar o Sistema**:
    a.  Inicie o Apache e o MySQL no seu XAMPP.
    b.  Abra seu navegador e acesse: `http://localhost/cofre-digital/public/`

## Uso da API

### Autenticação (`api/AuthController.php`)

-   **Registro de Usuário**
    -   **Endpoint**: `POST /api/AuthController.php?action=register`
    -   **Corpo da Requisição (JSON)**:
        ```json
        {
            "name": "Nome do Usuário",
            "email": "email@example.com",
            "password": "senha_segura"
        }
        ```
    -   **Resposta**: `{"status": "success", "message": "Usuário registrado com sucesso!"}` ou `{"status": "error", "message": "..."}`

-   **Login de Usuário**
    -   **Endpoint**: `POST /api/AuthController.php?action=login`
    -   **Corpo da Requisição (JSON)**:
        ```json
        {
            "email": "email@example.com",
            "password": "senha_segura"
        }
        ```
    -   **Resposta**: `{"status": "success", "message": "Login realizado com sucesso!", "data": {"name": "...", "email": "..."}}` ou `{"status": "error", "message": "..."}`. Define uma sessão PHP.

-   **Logout de Usuário**
    -   **Endpoint**: `POST /api/AuthController.php?action=logout`
    -   **Corpo da Requisição**: Vazio
    -   **Resposta**: `{"status": "success", "message": "Logout realizado com sucesso."}`. Destrói a sessão PHP.

### Gerenciamento de Segredos (`api/SecretController.php`)

*Todos os endpoints de segredos exigem autenticação (sessão PHP ativa).* 

-   **Criar Novo Segredo**
    -   **Endpoint**: `POST /api/SecretController.php?action=create`
    -   **Corpo da Requisição (JSON)**:
        ```json
        {
            "title": "Título do Segredo",
            "content": "Conteúdo sensível aqui..."
        }
        ```
    -   **Resposta**: `{"status": "success", "message": "Segredo guardado com sucesso!"}` ou `{"status": "error", "message": "..."}`

-   **Listar Segredos do Usuário**
    -   **Endpoint**: `GET /api/SecretController.php?action=list`
    -   **Resposta**: `{"status": "success", "data": [...]}` (lista de segredos) ou `{"status": "error", "message": "..."}`

-   **Visualizar Segredo Específico**
    -   **Endpoint**: `GET /api/SecretController.php?action=show&id={ID_DO_SEGREDO}`
    -   **Resposta**: `{"status": "success", "data": {...}}` (detalhes do segredo) ou `{"status": "error", "message": "..."}`

## Princípios de Security by Design Aplicados

-   **`password_hash` e `password_verify`**: Armazenamento seguro de senhas com hashing forte e verificação adequada.
-   **PDO Prepared Statements**: Todas as interações com o banco de dados utilizam prepared statements para prevenir ataques de SQL Injection.
-   **Validação de Entrada Centralizada**: A classe `Security` sanitiza e valida as entradas para mitigar XSS e outros ataques baseados em injeção.
-   **Autenticação via Sessão PHP**: Utiliza sessões nativas do PHP para gerenciar o estado de autenticação do usuário.
-   **Middleware de Autenticação**: `AuthMiddleware` garante que apenas usuários autenticados possam acessar endpoints protegidos.
-   **Isolamento por `user_id`**: Cada usuário só pode acessar e gerenciar seus próprios segredos, garantindo a privacidade dos dados.
-   **Resposta JSON Padronizada**: Todas as respostas da API seguem um formato JSON consistente, facilitando o consumo pelo frontend e a depuração.
-   **Não Exposição de Erros do PHP**: Em modo de produção (DEBUG_MODE = false), erros do PHP não são exibidos ao usuário, prevenindo a divulgação de informações sensíveis.

## Considerações para Auditoria (Cross-Audit)

O projeto foi estruturado pensando na facilidade de auditoria por equipes de Blue Team e Red Team:

-   **Blue Team**: A arquitetura modular e o uso de classes dedicadas para segurança (`Security.php`, `AuthMiddleware.php`) facilitam a análise da postura de defesa do sistema.
-   **Red Team**: A clareza do código e a aderência a padrões de segurança permitem que a equipe de ataque identifique e tente explorar potenciais vulnerabilidades, testando a robustez das defesas implementadas.

## Autor

**Leonardo Estevão Alves — Registro Acadêmico: 00250458**
