#!/bin/bash

# Fix permissions script for Laravel deployment
echo "Fixing Laravel permissions..."

# Set correct ownership
sudo chown -R www-data:www-data /var/www/laravel

# Set directory permissions
sudo find /var/www/laravel -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/laravel -type f -exec chmod 644 {} \;

# Set specific permissions for storage and bootstrap/cache
sudo chmod -R 775 /var/www/laravel/storage
sudo chmod -R 775 /var/www/laravel/bootstrap/cache

# Ensure www-data has write access to critical directories
sudo chown -R www-data:www-data /var/www/laravel/storage
sudo chown -R www-data:www-data /var/www/laravel/bootstrap/cache

# Create directory for product images if it doesn't exist
sudo mkdir -p /var/www/laravel/storage/app/public/products
sudo chown -R www-data:www-data /var/www/laravel/storage/app/public/products
sudo chmod -R 775 /var/www/laravel/storage/app/public/products

# Create storage link if it doesn't exist
cd /var/www/laravel
sudo -u www-data php artisan storage:link

echo "Permissions fixed successfully!" 