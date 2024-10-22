#FROM mcr.microsoft.com/devcontainers/php:1-8.2-bookworm
FROM php:8.3-apache

# Install php-mysql driver
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Remove default site
RUN rm -rf /var/www/html

# Copy code
COPY . /var/www