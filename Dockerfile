FROM php:8.2-apache

# Enable Apache rewrite module and install system dependencies
RUN a2enmod rewrite && \
    apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl npm && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i 's|<Directory /var/www/>|<Directory /var/www/html/public>|g' /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Optional: Set permissions (if needed)
# RUN chown -R www-data:www-data /var/www/html
