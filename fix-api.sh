#!/bin/bash

echo "Debugging and fixing Laravel API errors..."

# Enable error reporting and logging
cd /var/www/laravel

# Update .env to show errors
sudo sed -i "s/APP_DEBUG=false/APP_DEBUG=true/" .env
sudo sed -i "s/LOG_LEVEL=debug/LOG_LEVEL=debug/" .env

# Clear all Laravel caches
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan optimize:clear

# Fix CORS for API
sudo tee /var/www/laravel/config/cors.php > /dev/null <<EOF
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
EOF

# Check for common JWT issues
echo "Checking JWT configuration..."
if ! grep -q "JWT_SECRET" .env || [ -z "$(grep "JWT_SECRET" .env | cut -d '=' -f2)" ]; then
    echo "Regenerating JWT secret..."
    sudo -u www-data php artisan jwt:secret --force
fi

if ! grep -q "JWT_TTL" .env; then
    echo "Adding JWT_TTL to .env..."
    echo "JWT_TTL=60" | sudo tee -a .env
fi

# Ensure proper database configuration
echo "Verifying database connection..."
MYSQL_RESULT=$(sudo -u www-data php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Connection OK'; } catch (\Exception \$e) { echo \$e->getMessage(); }")
echo "Database connection test: $MYSQL_RESULT"

# Check recent logs
echo "Recent error logs:"
sudo tail -n 50 /var/www/laravel/storage/logs/laravel.log

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.3-fpm

echo ""
echo "Debug mode is now enabled. Check the error logs for more details:"
echo "sudo tail -f /var/www/laravel/storage/logs/laravel.log"
echo ""
echo "After identifying the issue, you can disable debug mode with:"
echo "sudo sed -i \"s/APP_DEBUG=true/APP_DEBUG=false/\" /var/www/laravel/.env"
echo ""
echo "Try your API request again to see more detailed error information." 