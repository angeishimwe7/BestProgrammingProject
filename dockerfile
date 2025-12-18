# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy all files into the container's web root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 for web server
EXPOSE 80
