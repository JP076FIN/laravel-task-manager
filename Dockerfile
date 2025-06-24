# Use a specific PHP version with Apache for stability
FROM php:8.2-apache

# Install system dependencies and PHP extensions
# Add 'gnupg' for NodeSource if you need Node/NPM for asset building during build process
# Add 'supervisor' if you plan to manage multiple processes (e.g., queues) with it
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    postgresql-client \
    zip \
    unzip \
    git \
    curl \
    npm \
    nodejs \
    gnupg \
    # Clean up apt caches to reduce image size
    && rm -rf /var/lib/apt/lists/* \
    \
    # Install PHP extensions
    && docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql mbstring exif pcntl bcmath gd \
    \
    # Enable Apache rewrite module (already good)
    && a2enmod rewrite

# Configure Apache to serve from Laravel's public directory
# Use a safer method for directory configuration
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf # Suppress ServerName warning

# Install Composer
# Using a separate stage for composer can be more efficient, but this works too.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory for the application
WORKDIR /var/www/html

# Copy application source code
# Using .dockerignore is crucial here to avoid copying unnecessary files (node_modules, .git, .env, etc.)
COPY . .

# Set correct permissions for Laravel storage and cache
# Important: Ensure www-data is the user Apache runs as in this image
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Run NPM dependencies and build assets (if you handle this in Docker build)
# This assumes you have a package.json and npm scripts.
# If you run `npm run dev` locally, you likely need `npm run build` for production.
# If you build assets locally and commit them, you can remove these lines.
# If you don't use frontend assets, also remove these lines.
RUN npm install
RUN npm run build # Use 'npm run production' for older Laravel Mix/Webpack, 'npm run build' for Vite

# Copy and make executable the entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 80 for Apache
EXPOSE 80

# Use the entrypoint script as the command to start the container
CMD ["/usr/local/bin/entrypoint.sh"]
