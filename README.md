
[link of the API online](https://app-product-management.herokuapp.com/)

### Installing
Inside the docker directory execute the command to get up the containers
```bash
$ docker-compose up -d
```

Get in the php container and execute the commands below
```bash
#getting in the container
$ docker container exec -it app bash
#commands
$ cp .env.example .env
$ php artisan key:generate
$ php artisan config:cache
$ composer dump-autoload
$ php artisan migrate --seed
$ php artisan passport:install
$ php artisan optimize:clear
```

Now the API should be available on this link: [API REST](http://localhost:8000).
