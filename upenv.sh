#!/bin/bash

echo "Updating Laravel .env file..."

# Create .env file directly
sudo tee /var/www/laravel/.env > /dev/null <<EOF
APP_NAME="Laravel API"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://54.255.138.254

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=your_secure_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="\${APP_NAME}"
VITE_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="\${PUSHER_HOST}"
VITE_PUSHER_PORT="\${PUSHER_PORT}"
VITE_PUSHER_SCHEME="\${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"

JWT_SECRET=
JWT_TTL=60
EOF

# Fix all permissions first
echo "Fixing permissions..."
sudo chown -R www-data:www-data /var/www/laravel
sudo find /var/www/laravel -type d -exec chmod 755 {} \;
sudo find /var/www/laravel -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/laravel/storage
sudo chmod -R 775 /var/www/laravel/bootstrap/cache

# Fix database permissions and setup
echo "Setting up database..."
sudo mysql -e "DROP USER IF EXISTS 'laravel_user'@'localhost';"
sudo mysql -e "CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'your_secure_password';"
sudo mysql -e "DROP DATABASE IF EXISTS laravel_db;"
sudo mysql -e "CREATE DATABASE laravel_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "GRANT ALL PRIVILEGES ON laravel_db.* TO 'laravel_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Generate app key
cd /var/www/laravel
sudo -u www-data php artisan key:generate --force

# Generate JWT secret
sudo -u www-data php artisan jwt:secret --force

# Clear config and cache
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear

# Create storage link
sudo -u www-data php artisan storage:link

# Run migrations with seed
sudo -u www-data php artisan migrate:fresh --seed

echo "Environment updated successfully!" 