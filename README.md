<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Setup  first Start

     composer install

copy .env.example to .env and fill your DB credentials in the .env file.

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=wongnoon
    DB_USERNAME=root
    DB_PASSWORD=

Add your GOOGLE_KEY in .env

    GOOGLE_KEY=abc

Generate key
    php artisan key:generate

Migrate

     php artisan migrate

Please run the following commands to clear all cache from the project.

    php artisan optimize


Clear cache.

    composer dump-autoload
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear

##Serve for development

    php artisan serve
    

