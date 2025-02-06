# Base php apache image
FROM php:8.3-apache

# Install git & zip
RUN apt-get update && apt-get install -y git libzip-dev

# Install php-mysql driver
RUN docker-php-ext-install mysqli pdo pdo_mysql zip

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
#RUN composer install

# Update apache configuration
RUN sed -ri -e 's!/var/www/html!/var/www/public_html!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!/var/www/public_html!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/docker-php.conf

# Enable apache rewrite
RUN a2enmod rewrite

# Update user & folder permissions
RUN usermod -u 1000 www-data && chmod a+x "$(pwd)"

# Start apache
CMD apachectl start