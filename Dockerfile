# Base php apache image
FROM php:8.3-apache

# Install php-mysql driver
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Update composer
RUN composer self-update

# Remove default site
RUN rm -rf /var/www/html

# Copy code
COPY . /var/www

# Set working directory
WORKDIR /var/www

# Install dependancies
RUN composer install

# Update apache configuration
RUN sed -ri -e 's!/var/www/html!/var/www/public_html!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!/var/www/public_html!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/docker-php.conf