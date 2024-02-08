# Use an official PHP runtime with Apache server
FROM php:7.4-apache

# Set the working directory to the Apache document root
WORKDIR /var/www/html

# Copy the application files to the container
COPY . /var/www/html

# Install any PHP extensions you need (you might not need these)
RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

# Apache is already configured to serve the files, and PHP is installed
