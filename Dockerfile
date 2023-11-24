# Use an official PHP runtime as a base image
FROM php:8.1-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html

# Set noninteractive mode for apt
ARG DEBIAN_FRONTEND=noninteractive

# Install apt-utils to suppress debconf warning and unzip
RUN apt-get update && apt-get install -y apt-utils unzip

# Install any dependencies your PHP project needs (e.g., PHP extensions)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Install Composer
RUN apt-get update && \
    apt-get install -y unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Apache configuration (you may need to adjust this based on your project)
RUN a2enmod rewrite

# Add a ServerName directive to suppress Apache warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Add these lines to enable directory listing and specify the default index file
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf

# Expose port 80 for the web server to listen on
EXPOSE 80

# Define the command to run your PHP application
CMD ["apache2-foreground"]
