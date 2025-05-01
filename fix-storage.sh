#!/bin/bash

echo "Setting up storage for product images..."

# Create products directory structure
sudo mkdir -p /var/www/laravel/storage/app/public/products

# Set proper permissions
sudo chown -R www-data:www-data /var/www/laravel/storage
sudo chmod -R 775 /var/www/laravel/storage/app/public/products

# Recreate storage link to ensure it's working
cd /var/www/laravel
sudo -u www-data php artisan storage:link --force

echo "Storage setup complete! Your products directory is now available at:"
echo "/var/www/laravel/storage/app/public/products"
echo ""
echo "It will be publicly accessible at:"
echo "http://54.255.138.254/storage/products/"
echo ""
echo "You can upload your product images to:"
echo "/var/www/laravel/storage/app/public/products/" 