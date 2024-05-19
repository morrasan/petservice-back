# Pet Service - back (REST API)
The simple Pet service for the store pets

## Stack version
1. PHP 8.2
2. Laravel 10.x
3. MySQL 5.7
4. Docker

## Install / run
1. git clone https://github.com/morrasan/petservice-back.git
2. docker compose up
3. open terminal on the `petservice-app` container and run commands:
    - composer install
    - .env.example -> .env
    - php artisan migrate
    - php artisan optimize
    - php artisan storage:link

## Environment
1. http://localhost:8008 - REST API 
