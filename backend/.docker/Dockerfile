# Stage for Composer dependencies
FROM composer:2.4 as vendor

# Base image with PHP and Apache
FROM php:8.3-apache

# Install necessary PHP extensions and intl
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

# Copy application code into the container
COPY ./src /var/www/html/

# Set appropriate permissions for the application directory
RUN chown -R www-data:www-data /var/www

# Copy Composer binary from previous stage
COPY --from=vendor /usr/bin/composer /usr/bin/composer

# Set working directory to API root
WORKDIR /var/www/html/

# Copy only composer.json initially to leverage Docker layer caching
COPY ./src/composer.json composer.json

# Swagger permissions
RUN chown -R www-data:www-data /var/www/html/swagger
RUN chmod -R 755 /var/www/html/swagger

# Run Composer install
RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist

# Optimize autoloader after installation
RUN composer dump-autoload --optimize

# Expose port 80 for web
EXPOSE 80

# Start Apache server when the container starts
CMD ["apache2-foreground"]
