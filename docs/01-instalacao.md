# Guia de instalação

## Pré-requisitos

-   Docker e Docker compose

## Passos para instalação

### 1. Clone o repositório:

Clone o repositório da forma que preferir:

```sh
# Via SSH
git clone git@github.com:gabrielaborborema/transferencias.git &&
cd transferencias
```

```sh
# Via HTTP
git clone https://github.com/gabrielaborborema/transferencias.git &&
cd transferencias
```

### 2. Crie a .env

Por praticidade, o arquivo .env.example já está configurado com todas as informações necessárias para rodar o projeto, então basta apenas copiar:

```sh
cp .env.example .env
```

### 3. Baixe os vendors

Como o sail ainda não está instalado, precisamos baixar os vendors via docker:

```sh
docker run --rm \
    -v "$(pwd):/app" \
    --user "$(id -u):$(id -g)" \
    composer install --ignore-platform-reqs
```

### 4. Inicie o sail

Utilize o seguinte comando para iniciar o sail com os containers da aplicação e banco de dados:

```sh
./vendor/bin/sail up -d
```

### 5. Gere chave

Para gerar a chave de criptografia da aplicação rode:

```sh
./vendor/bin/sail artisan key:generate
```

### 6. Execute as migrações

Para gerar as tabelas no banco da dados com alguns usuários mockados rode:

```sh
./vendor/bin/sail artisan migrate:fresh --seed
```

### 7. Execute os workers

Para o sistema de notificação via email funcionar é preciso executar os workers:

```sh
./vendor/bin/sail artisan queue:work
```

### 8. Execute o servidor vite

Para o frontend funcionar corretamente é necessário rodar o servidor vite:

```sh
./vendor/bin/sail npm install &&
./vendor/bin/sail npm run dev
```

## 8. Acesse a aplicação

A aplicação vai estar rodando na porta 8080. Basta acessar http://localhost:8080.

Como ainda não foi desenvolvido o depósito, existirá um usuário teste com R$1000 na conta, porém fique a vontade para criar um novo usuário se desejar.
login: teste@email.com
senha: password
