#!/usr/bin/env bash
file="/var/www/.env"
if [ ! -f "$file" ]
then
    cd /var/www
    chmod 777 -R ./storage
    cp .env.example .env
    php artisan key:generate
    migrate
    seed
    seed --class=InicialSeeder
    seedgod
    php artisan passport:install
fi
exec php-fpm
