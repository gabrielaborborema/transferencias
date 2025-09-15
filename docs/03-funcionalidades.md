# Principais Funcionalidades

## 1. Registro

-   O cadastro de usuários é feito utilizando Nome Completo, CPF/CNPJ, Email e Senha.
-   CPF/CNPJ e Email são campos únicos no banco de dados, permitindo apenas um cadastro por valor.
-   Os usuários podem ser do tipo Lojista (`type = store`) ou Usuário Comum (`type = common`).
-   O campo de CPF/CNPJ altera dinâmicamente de acordo com o tipo de usuário.
-   A senha exige confirmação.
-   A lógica é controlada pelo componente `app/Livewire/Auth/Register.php`.

## 2. Login e Autenticação

-   O login do usuário é feita utilizando Email e Senha.
-   Existe a opção para salvar a seção com o botão 'Lembrar-me'.
-   A lógica é controlada pelo componente `app/Livewire/Auth/Login.php`.

## 3. Tranferência Financeira

-   O fluxo é iniciado pelo componente `app/Livewire/Home.php`.
-   A lógica de negócio é processada pela classe `app/Services/TransactionService.php`.
-   **Regras de Negócio:**
    -   Um usuário não pode transferir para ele mesmo.
    -   Lojistas não podem enviar transferências.
    -   É necessário ter saldo suficiente para a quantia a ser enviada.
    -   É feita uma autorização pelo serviço externo `https://util.devi.tools/api/v2/authorize`.
-   As operações de transferencia no banco de dados são feitas via `DB::transaction` para garantir integridade.
-   Após a transferência bem sucedida, o job `app/Jobs/NotifyEmail.php` é despachado para a fila.

## 4. Notificação Via Email

- A lógica do Job é feita em `app/Jobs/NotifyEmail.php`.
- Contata o serviço externo para simular o envio de emails `https://util.devi.tools/api/v1/notify`.
- Caso falhe o envio é feito outras 5 tentativas (podendo ter no máximo 3 falhas), com backOff respectivamente de 10s, 1m, 5m, 30m.
