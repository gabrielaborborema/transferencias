# Tranferencias

Essa aplicação é uma plataforma simples de transferência de dinheiro, tendo como funcionalidades cadastro, login, transferência e depósito.

## Funcionalidades Principais

-   (OK) Cadastro de usuário (comum ou lojista) com validação de CPF/CNPJ.
-   (OK) Login e autenticação de usuários.
-   (OK) Transferência financeira entre usuários via email ou CPF/CNPJ com validação de regras de negócio.
-   (OK) Notificação de transações via email simulada utilizando filas.
-   (TO-DO) Depósito de dinheiro.
-   (TO-DO) Transferência via histórico de contatos.

## Stack Utilizada

-   **Backend:** Laravel 12
-   **Frontend:** Livewire 3 e AlpineJS
-   **Estilização:** TailwindCSS 4 e WireUI
-   **Banco de Dados:** PostgreSQL
-   **Ambiente de Desenvolvimento:** Laravel Sail (Docker)
-   **Testes:** Pest

## Quickstart

1. Clone o repositório `git clone ...`
2. Crie o .env a partir do .env.example: `cp .env.example .env`
3. Baixe os vendors: `docker run --rm -v "$(pwd):/app" --user "$(id -u):$(id -g)" composer install --ignore-platform-reqs`
4. Inicie o sail: `./vendor/bin/sail up -d`
5. Gere a chave: `./vendor/bin/sail artisan key:generate`
6. Execute as migrações: `./vendor/bin/sail artisan migrate:fresh --seed`
7. Execute os workers: `./vendor/bin/sail artisan queue:work`
8. Execute o servidor vite: `./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev`
9. Acesse a aplicação: http://localhost:8080

> Para um guia de instalação mais detalhado, veja a [**documentação completa**](./docs/01-instalacao.md).
