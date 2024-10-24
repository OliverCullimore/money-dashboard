#FROM mcr.microsoft.com/devcontainers/php:1-8.2-bookworm
FROM php:8.3-apache

# Install php-mysql driver
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Update composer
RUN composer self-update

# Set working directory
WORKDIR /var/www

# Copy code
COPY . .

# Replace default site
RUN rm -rf /var/www/html && mv /var/www/public_html /var/www/html

# Install dependancies
RUN composer install