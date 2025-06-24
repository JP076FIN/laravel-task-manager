#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Inject .env from Render Secret if not already present
# This part is good for handling sensitive .env variables
if [ ! -f .env ] && [ ! -z "$LARAVEL_ENV_FILE" ]; then
    echo "$LARAVEL_ENV_FILE" > .env
    echo ".env file created from LARAVEL_ENV_FILE secret."
else
    echo ".env file already exists or LARAVEL_ENV_FILE secret is empty. Skipping injection."
fi

# Permissions (ensure these are applied early)
echo "Setting permissions for storage and bootstrap/cache..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Generate key if empty (run as www-data)
echo "Checking APP_KEY..."
if grep -q "APP_KEY=$" .env; then
    echo "Generating new APP_KEY..."
    su -s /bin/bash www-data -c "php artisan key:generate"
else
    echo "APP_KEY already set."
fi

# --- Database Connection and Migrations ---

# Wait for the database to be ready
# Requires 'postgresql-client' in your Dockerfile for pg_isready
# This relies on DB_HOST, DB_PORT, DB_USERNAME being available as environment variables.
# Even if you use DATABASE_URL, you often need these individual ones for pg_isready.
echo "Waiting for database to be ready..."
until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" > /dev/null 2>&1; do
  echo "Database is unavailable - sleeping"
  sleep 2
done
echo "Database is ready!"

# Run database migrations (run as www-data)
echo "Running database migrations..."
su -s /bin/bash www-data -c "php artisan migrate --force"

# --- Laravel Caching ---
# Run these AFTER migrations, as migrations might change the schema
# that cached configurations rely on.
echo "Clearing and caching configuration..."
su -s /bin/bash www-data -c "php artisan config:clear"
su -s /bin/bash www-data -c "php artisan config:cache"

echo "Caching routes..."
su -s /bin/bash www-data -c "php artisan route:cache"

echo "Caching views..."
su -s /bin/bash www-data -c "php artisan view:cache"

# --- Start Apache ---
echo "Starting Apache..."
exec apache2-foreground
