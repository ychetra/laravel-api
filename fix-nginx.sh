#!/bin/bash

echo "Fixing Nginx configuration and storage access..."

# Fix Nginx configuration to properly serve static files
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

    # Properly configure storage access
    location /storage {
        alias /var/www/laravel/storage/app/public;
        try_files \$uri \$uri/ =404;
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

# Ensure storage link exists and has proper permissions
sudo rm -f /var/www/laravel/public/storage
cd /var/www/laravel
sudo -u www-data php artisan storage:link --force

# Set proper permissions for storage directory
sudo chown -R www-data:www-data /var/www/laravel/storage
sudo chmod -R 775 /var/www/laravel/storage
sudo mkdir -p /var/www/laravel/storage/app/public/products
sudo chown -R www-data:www-data /var/www/laravel/storage/app/public/products
sudo chmod -R 775 /var/www/laravel/storage/app/public/products

# Ensure Nginx can access storage
sudo chown -R www-data:www-data /var/www/laravel/public
sudo usermod -a -G www-data nginx

# Restart Nginx to apply configuration changes
sudo systemctl restart nginx

# Verify security group allows HTTP
echo ""
echo "Please make sure your AWS EC2 security group allows inbound traffic on port 80 (HTTP)"
echo "You can configure this in the AWS Console:"
echo "1. Go to EC2 > Security Groups"
echo "2. Select the security group attached to your instance"
echo "3. Edit inbound rules"
echo "4. Add rule: HTTP (port 80) from Anywhere (0.0.0.0/0)"
echo ""
echo "After applying these changes, your site should be accessible at:"
echo "http://54.255.138.254"
echo ""
echo "And your product images should be accessible at:"
echo "http://54.255.138.254/storage/products/[image-name]" 