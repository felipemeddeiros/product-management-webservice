
[link online](https://app-product-management.herokuapp.com/)

### Instalando a API REST com Laravel
Para permitir a instalação em qualquer sistema operacional eu utilizei o [Docker](https://www.docker.com/products/docker-desktop).

Apoś instalar o docker, clone o repositório no diretório onde você mantem seus projetos.

Pelo terminal de comando, entre na pasta. E depois entre na pasta da API do laravel que esta com nome de ***webservice*** e suba os containers do docker.
```sh
$ cd product-management-app
$ cd webservice
$ docker-compose up -d
```

Após os containers subirem, execute o comando para atualizar os packages necessários para o funcionamento da API.
```sh
$ docker-compose exec app composer install
```

Execute o seguinte comando para voltar ao diretório anterior e setar as permissões do seu usuário no diretório
```sh
$ cd ..
$ sudo chown -R $USER:$USER webservice
```

Entre novamente no diretório ***webservice***, depois no diretório **database**, e crie o seguinte arquivo ***database.sqlite***. Esse será o nosso banco de dados.
```sh
$ cd webservice/database
$ touch database.sqlite
```

No diretório ***webservice***, Gere o arquivo .env de configuração da API.
```sh
$ cp .env.example .env
```

Execute o comando para gerar a key da API.
```sh
$ docker-compose exec app php artisan key:generate
```

Execute o comando para setar as configurações realizadas.
```sh
$ docker-compose exec app php artisan config:cache
```

Execute o comando para regenerar as classes.
```sh
$ docker-compose exec app composer dump-autoload
```

Execute as migrations no banco de dados.
```sh
$ docker-compose exec app php artisan migrate
```

Execute as seeders no banco de dados.
```sh
$ docker-compose exec app php artisan db:seed
```

Execute o comando para gerar as keys de autenticação da API.
```sh
$ docker-compose exec app php artisan passport:install
```

Execute o comando para limpar e recarregar as configurações.
```sh
$ docker-compose exec app php artisan optimize:clear
```

Execute o comando para criar um link para o armazenamento dos arquivos.
```sh
$ docker-compose exec app php artisan storage:link
```

Criar o diretório ***products*** que será o diretório padrão para salvar as imagens do produto. E mover a imagem padrão para lá.
```sh
$ mkdir storage/app/public/products
$ cp default.jpeg storage/app/public/products/
```

> Caso você tenha problema de permissão para executar os comandos acima. Inclua ***sudo*** antes de executar todos os comandos.

Agora a API deve estar disponível no seguinte link: [API REST](http://localhost:8000).