#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# --- .env File Injection ---
echo "Attempting to inject .env file from LARAVEL_ENV_FILE secret..."
if [ ! -z "$LARAVEL_ENV_FILE" ]; then
    echo "$LARAVEL_ENV_FILE" > .env
    echo ".env file created/overwritten successfully."
else
    echo "Error: LARAVEL_ENV_FILE secret is empty or not set. Cannot inject .env file."
    echo "Make sure LARAVEL_ENV_FILE secret is configured correctly on Render with your .env content."
    exit 1 # Exit if the critical .env cannot be injected
fi

# --- Permissions ---
echo "Setting permissions for storage and bootstrap/cache..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# --- APP_KEY Generation ---
echo "Checking APP_KEY..."
if grep -q "APP_KEY=$" .env; then
    echo "Generating new APP_KEY..."
    su -s /bin/bash www-data -c "php artisan key:generate"
else
    echo "APP_KEY already set."
fi

# --- Database Connection Check (with retry limit and detailed output) ---
# pg_isready needs DB_HOST, DB_PORT, DB_USERNAME as direct shell variables.
# We source the .env file to make them available for this check.
echo "Preparing database connection details for pg_isready..."
set -a # automatically export all variables to subshells
source .env # read .env into current shell variables
set +a # stop automatically exporting variables

# Define max retries and current retry counter
MAX_RETRIES=5 # Set to a low number for faster debugging
RETRY_COUNT=0

# --- DEBUGGING: ECHO THE VARIABLES BEING USED ---
echo "DEBUG: DB_HOST is: '$DB_HOST'"
echo "DEBUG: DB_PORT is: '$DB_PORT'"
echo "DEBUG: DB_USERNAME is: '$DB_USERNAME'"
# --- END DEBUGGING ---

echo "Waiting for database to be ready..."
until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" || [ "$RETRY_COUNT" -ge "$MAX_RETRIES" ]; do
  echo "Database is unavailable - sleeping (attempt $((RETRY_COUNT + 1))/$MAX_RETRIES)"

  # --- DEBUGGING: SHOW RAW PG_ISREADY OUTPUT ---
  echo "DEBUG: Raw pg_isready output for attempt $((RETRY_COUNT + 1)):"
  pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" # <-- Removed > /dev/null 2>&1
  # --- END DEBUGGING ---

  sleep 2
  RETRY_COUNT=$((RETRY_COUNT + 1))
done

if [ "$RETRY_COUNT" -ge "$MAX_RETRIES" ]; then
  echo "Error: Database did not become ready after $MAX_RETRIES attempts. Exiting."
  exit 1 # Exit with an error if database is not ready
else
  echo "Database is ready!"
fi

# --- Database Migrations ---
echo "Running database migrations..."
su -s /bin/bash www-data -c "php artisan migrate --force"

# --- Laravel Caching ---
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
