# API MultiPedidos

## Instruções para executar a API no seu ambiente Docker.

---

## Requisitos Técnicos
- Docker versão 24+

---

## Instruções de Funcionamento

1. Clone este repositório:

```bash
git clone git@github.com:netopvh/multipedidos_api.git
```

2. Navegue no diretório do projeto.

```bash
cd multipedidos_api
```

3. Execute o comando para rodar os containers.

```bash
docker compose up -d --build
```

obs: O comando deve ser executado na pasta raiz.

4. Entre dentro do container docker do projeto da API, utilize os seguintes comandos.

```bash
docker compose exec multipedidos_api bash
```

```bash
composer install
```

```bash
php artisan key:generate
```

```bash
php artisan migrate
```

Os comandos acima irão deixar a api pronta para executar, caso queira inserir os seed do banco de dados basta executar o seguinte comando:

```bash
php artisan db:seed
```

A aplicação foi desenvolvida em TDD e na pasta de testes possui todos os testes que fora realizados na api e para executar os testes para executar o seguinte comando.

```bash
php artisan test
```

Para executar requisições via Postman/Insomnia utilize a seguinte url:

```
http://localhost:8080
```

A aplicação conta com a api toda documentada utilizando o Swagger, para acessar o swagger basta acessar a seguinte URL.

```
http://localhost:8080/api/docs
```

Qualquer dúvida favor entrar em contato através do email: netopvh@gmail.com