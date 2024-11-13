# Base image with PHP and Apache
FROM php:8.3-apache

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    unzip && \
    docker-php-ext-configure intl && \
    docker-php-ext-install zip intl && \
    rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod ssl

# Copy application code
COPY ./src /var/www/html/

# Set appropriate permissions for the application directory
RUN chown -R www-data:www-data /var/www/html

# Copy composer files for API dependencies
WORKDIR /var/www/html/api
COPY ./src/api/composer.json composer.json
COPY ./src/api/composer.lock composer.lock

# Install composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Return to main directory
WORKDIR /var/www/html

# Add custom Apache VirtualHost configuration
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Add shared .htaccess file
#COPY .docker/.htaccess /var/www/html/.htaccess

# Expose port 80 for web
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
