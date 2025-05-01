#!/bin/bash

# This script will be run at system startup

# Start MySQL
sudo systemctl start mysql

# Start Nginx
sudo systemctl start nginx

# Start PHP-FPM
sudo systemctl start php8.1-fpm

# Start Laravel Queue Worker
sudo systemctl start laravel-worker

# Ensure proper permissions
sudo chown -R www-data:www-data /var/www/laravel/storage
sudo chmod -R 775 /var/www/laravel/storage

echo "Laravel services started successfully" > /var/log/laravel-startup.log
date >> /var/log/laravel-startup.log 