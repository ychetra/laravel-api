#!/bin/bash

# One-script Laravel deployment for AWS EC2
# This script handles everything: setup, configuration, and auto-start on boot

# Exit on error
set -e

echo "========== LARAVEL EC2 DEPLOYMENT SCRIPT =========="
echo "Starting deployment process..."

# Update system
echo "Updating system packages..."
sudo apt-get update
sudo apt-get upgrade -y

# Install essential packages
echo "Installing required packages..."
sudo apt-get install -y git unzip nginx mysql-server php-fpm php-mysql php-mbstring php-xml php-bcmath php-curl php-zip php-gd php-cli

# Install Composer
echo "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Configure MySQL
echo "Configuring MySQL..."
# Start MySQL if not running
sudo systemctl start mysql
sudo systemctl enable mysql

# Create database and user
sudo mysql -e "CREATE DATABASE IF NOT EXISTS laravel_db;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'laravel_user'@'localhost' IDENTIFIED BY 'your_secure_password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON laravel_db.* TO 'laravel_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Set up project directory
PROJ_DIR=/var/www/laravel
echo "Setting up project in $PROJ_DIR..."
sudo mkdir -p $PROJ_DIR
sudo chown -R ubuntu:ubuntu $PROJ_DIR

# Clone repository or create new Laravel project
read -p "Do you want to clone a Git repository? (y/n): " clone_choice
if [[ $clone_choice == "y" ]]; then
    read -p "Enter your Git repository URL: " repo_url
    git clone $repo_url $PROJ_DIR
else
    echo "Creating a fresh Laravel installation..."
    composer create-project --prefer-dist laravel/laravel $PROJ_DIR
fi

# Set permissions
cd $PROJ_DIR
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 755 $PROJ_DIR

# Install dependencies
echo "Installing Laravel dependencies..."
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
echo "Running database migrations and seeders..."
php artisan migrate:fresh --seed

# Set up storage link for public access to images
echo "Creating storage link for public access..."
php artisan storage:link

# Create image directories with proper permissions
echo "Setting up image directories..."
sudo mkdir -p $PROJ_DIR/storage/app/public/products
sudo chown -R www-data:www-data $PROJ_DIR/storage
sudo chmod -R 775 $PROJ_DIR/storage

# Get EC2 public IP
EC2_PUBLIC_IP=$(curl -s http://169.254.169.254/latest/meta-data/public-ipv4)

# Set up Nginx
echo "Configuring Nginx..."
sudo tee /etc/nginx/sites-available/laravel > /dev/null <<EOF
server {
    listen 80;
    server_name $EC2_PUBLIC_IP;
    root /var/www/laravel/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Enable the site and remove default
sudo ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Set up PHP version
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
echo "Detected PHP version: $PHP_VERSION"

# Set up Laravel worker service for queue processing
echo "Setting up Laravel worker service..."
sudo tee /etc/systemd/system/laravel-worker.service > /dev/null <<EOF
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

# Set up startup script service
echo "Creating startup service..."
sudo tee /etc/systemd/system/laravel-startup.service > /dev/null <<EOF
[Unit]
Description=Laravel Startup Service
After=network.target

[Service]
Type=oneshot
ExecStart=/bin/bash -c "systemctl start mysql nginx php$PHP_VERSION-fpm laravel-worker && chown -R www-data:www-data /var/www/laravel/storage && chmod -R 775 /var/www/laravel/storage"
RemainAfterExit=yes

[Install]
WantedBy=multi-user.target
EOF

# Enable and start services
echo "Enabling and starting services..."
sudo systemctl daemon-reload
sudo systemctl enable nginx
sudo systemctl enable php$PHP_VERSION-fpm
sudo systemctl enable laravel-worker
sudo systemctl enable laravel-startup

sudo systemctl start nginx
sudo systemctl start php$PHP_VERSION-fpm
sudo systemctl start laravel-worker

# Final message
echo "========== DEPLOYMENT COMPLETE =========="
echo "Your Laravel application is now deployed!"
echo "Website URL: http://$EC2_PUBLIC_IP"
echo "Image access URL: http://$EC2_PUBLIC_IP/storage/products/your-image.jpg"
echo ""
echo "To upload images, use the following command from your local machine:"
echo "scp -i your-key.pem -r ./product-images/* ubuntu@$EC2_PUBLIC_IP:/tmp/"
echo "Then on the server, run:"
echo "sudo mv /tmp/* /var/www/laravel/storage/app/public/products/"
echo "sudo chown -R www-data:www-data /var/www/laravel/storage/app/public/products/"
echo ""
echo "Your application will automatically start on system reboot."
echo "==========================================" 