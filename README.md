# Tranferencias

O projeto foi desenvolvido utilizando o Sail, então para inicializar apenas é necessário que tenha o docker instalado na sua máquina.

## 1. Composer install

Após clonar o projeto, rode o seguinte comando para baixar os vendors através do docker:

```
docker run --rm \
    -v "$(pwd):/app" \
    --user "$(id -u):$(id -g)" \
    composer install --ignore-platform-reqs
```

## 2. Clone a .env

Deixei a .env já com todas as configurações necessárias, só clonar a .env.example e nomear para .env

## 3. Rodar o sail

Utilize o seguinte comando para iniciar o sail com os containers:

```
./vendor/bin/sail up -d
```

Depois rode o servidor vite com os seguintes comandos:

```
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

## 4. Gerar chave

Para rodar a chave encriptada da aplicação rode:

```
./vendor/bin/sail artisan key:generate
```

## 5. Gerar migrações com seed

Para gerar as tabelas no banco da dados rode:

```
./vendor/bin/sail artisan migrate:fresh --seed
```

## 6. Acesse a aplicação

A aplicação vai estar rodando na porta 8080. Basta acessar http://localhost:8080.

Existirá um usuário teste com R$1000 na conta, porém fique a vontade para criar um novo usuário se desejar.
login: teste@email.com
senha: password
