#!/bin/bash

# Inject .env from environment variable if not already present
if [ ! -f .env ] && [ ! -z "$LARAVEL_ENV_FILE" ]; then
    echo "$LARAVEL_ENV_FILE" > .env
fi

# Permissions
chmod -R 775 storage bootstrap/cache

# Generate key if it's still empty
if grep -q "APP_KEY=$" .env; then
    php artisan key:generate
fi

# Cache and optimize Laravel
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache server
exec apache2-foreground
