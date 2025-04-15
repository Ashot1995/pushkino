#!/bin/bash
cd /var/www

sleep 10

php artisan migrate --force
php artisan optimize
php artisan test

sleep 2

/usr/bin/supervisord
