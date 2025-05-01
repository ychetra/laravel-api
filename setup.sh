#!/bin/bash

# Exit on error
set -e

# Update system
sudo apt-get update
sudo apt-get upgrade -y

# Install essential packages
sudo apt-get install -y git unzip nginx mysql-server php-fpm php-mysql php-mbstring php-xml php-bcmath php-curl php-zip php-gd php-cli

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Create database and user
sudo mysql -e "CREATE DATABASE laravel_db;"
sudo mysql -e "CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'your_secure_password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON laravel_db.* TO 'laravel_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Set up project directory
PROJ_DIR=/var/www/laravel
sudo mkdir -p $PROJ_DIR
sudo chown -R ubuntu:ubuntu $PROJ_DIR

# Clone your repository (replace with your repository URL)
git clone https://github.com/yourusername/your-laravel-repo.git $PROJ_DIR
# OR if you don't have a repository yet, download a fresh Laravel install
# composer create-project --prefer-dist laravel/laravel $PROJ_DIR

# Set permissions
cd $PROJ_DIR
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 755 $PROJ_DIR

# Install dependencies
cd $PROJ_DIR
composer install --no-interaction --prefer-dist --optimize-autoloader

# Set up environment variables
cp .env.example .env
php artisan key:generate

# Update .env file with database credentials
sed -i "s/DB_DATABASE=.*/DB_DATABASE=laravel_db/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=laravel_user/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=your_secure_password/" .env

# Run migrations and seeders
php artisan migrate:fresh --seed

# Set up storage link for public access to images
php artisan storage:link

# Set up Nginx
sudo tee /etc/nginx/sites-available/laravel > /dev/null <<'EOF'
server {
    listen 80;
    server_name your-ec2-public-ip-or-domain.com;
    root /var/www/laravel/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Enable the site
sudo ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx

# Set up service for automatic startup
sudo tee /etc/systemd/system/laravel-worker.service > /dev/null <<'EOF'
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/laravel/artisan queue:work

[Install]
WantedBy=multi-user.target
EOF

# Enable and start the service
sudo systemctl enable laravel-worker
sudo systemctl start laravel-worker

# Create an image upload directory with proper permissions
sudo mkdir -p /var/www/laravel/storage/app/public/products
sudo chown -R www-data:www-data /var/www/laravel/storage
sudo chmod -R 775 /var/www/laravel/storage

echo "Installation complete!"
echo "Your Laravel application should now be accessible at http://your-ec2-public-ip" 