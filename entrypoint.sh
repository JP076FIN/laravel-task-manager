#!/bin/bash

# Inject .env from Render Secret if not already present
if [ ! -f .env ] && [ ! -z "$LARAVEL_ENV_FILE" ]; then
    echo "$LARAVEL_ENV_FILE" > .env
fi

# Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Generate key if empty
if grep -q "APP_KEY=$" .env; then
    su -s /bin/bash www-data -c "php artisan key:generate"
fi

# Laravel caches
su -s /bin/bash www-data -c "php artisan config:clear"
su -s /bin/bash www-data -c "php artisan config:cache"
su -s /bin/bash www-data -c "php artisan route:cache"
su -s /bin/bash www-data -c "php artisan view:cache"

# Start Apache
exec apache2-foreground
