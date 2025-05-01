#!/bin/bash

# Complete Laravel setup script
echo "Completing Laravel setup..."

# Set environment variables for database
cd /var/www/laravel
cp .env.example .env
sed -i "s/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/" .env
sed -i "s/DB_DATABASE=laravel/DB_DATABASE=laravel_db/" .env
sed -i "s/DB_USERNAME=root/DB_USERNAME=laravel_user/" .env
sed -i "s/DB_PASSWORD=/DB_PASSWORD=your_secure_password/" .env

# Generate application key
php artisan key:generate

# Configure JWT
php artisan jwt:secret

# Run migrations and seeders
php artisan migrate:fresh --seed

# Configure Nginx
sudo tee /etc/nginx/sites-available/laravel > /dev/null <<EOF
server {
    listen 80;
    server_name 54.255.138.254;
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
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Enable site configuration
sudo ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Restart Nginx and PHP-FPM
sudo systemctl restart nginx
sudo systemctl restart php8.3-fpm

echo "Laravel setup completed successfully! Site available at http://54.255.138.254" 