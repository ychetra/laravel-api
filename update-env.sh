#!/bin/bash

echo "Updating Laravel .env file..."

# Copy our custom .env file
sudo cp ec2-env /var/www/laravel/.env

# Set proper ownership
sudo chown www-data:www-data /var/www/laravel/.env

# Generate app key
cd /var/www/laravel
sudo -u www-data php artisan key:generate

# Generate JWT secret
sudo -u www-data php artisan jwt:secret

# Clear config and cache
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear

# Run migrations with seed
sudo -u www-data php artisan migrate:fresh --seed

echo "Environment updated successfully!" 