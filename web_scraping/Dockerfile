# Use the official PHPimage as the base image
FROM php:8.1-zts-bullseye

# Set the working directory
WORKDIR /var/www/html

# Copy the local Laravel project files to the container
COPY . .

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies
RUN composer install

# Expose port 8000
EXPOSE 8000

# Start Apache
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
