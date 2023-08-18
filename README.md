
# Setup Docker Para o teste iHOld com Laravel 10, php 8.2 , mysql e Nginx

feactures e bibliotecas utilizadas
```sh
- Migrations
- Seeders
- Resource
- FormRequest
- PHPUnit
- spatie/laravel-ignition: 2.0 (para o ACL)
- Swagger (para a documentação)
```

Clone os Arquivos do Laravel
```sh
git clone https://github.com/mateusgabriel042/test-ihold.git
```

Crie o Arquivo .env
```sh
cp .env.example .env
```

Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME="ihold Teste"
APP_URL=http://localhost:8989

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=teste-ihold
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Suba os containers do projeto
```sh
docker-compose up -d
```

Acessar o container
```sh
docker-compose exec app bash
```


Instalar as dependências do projeto
```sh
composer install
```


Gerar a key do projeto Laravel
```sh
php artisan key:generate
```

Exectuar as migrations
```sh
php artisan migrate
```

Executar as seeds
```sh
php artisan db:seed
```

Execute o comando para testar a aplicação com o PHPUnit
```sh
php artisan test
```

Acessar o projeto
[http://localhost:8989/swagger/index](http://localhost:8000/swagger/index)
