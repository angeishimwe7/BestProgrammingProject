# Use PHP 8.2 with Apache as the base image [cite: 1]
FROM php:8.2-apache

# 1. Install necessary PHP extensions for MySQL [cite: 1]
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 2. Enable Apache rewrite module (common for PHP apps)
RUN a2enmod rewrite

# 3. Set the working directory inside the container 
WORKDIR /var/www/html/

# 4. Copy your project files into the container 
COPY . /var/www/html/

# 5. Fix permissions: Set the owner to 'www-data' so Apache can read your files
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# 6. Tell Docker the container listens on port 80 
EXPOSE 80
