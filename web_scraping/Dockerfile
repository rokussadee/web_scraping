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

# Install Python and pip
RUN apt-get update && apt-get install -y python3 python3-pip

# Copy Python scripts to the container
COPY app/PythonScripts /app/PythonScripts

# Install Python dependencies
RUN pip3 install --no-cache-dir -r /app/PythonScripts/requirements.txt

# Install Laravel dependencies
RUN composer install

# Add a script to wait for the database before running migrations
COPY wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod +x /usr/local/bin/wait-for-it

# Copy the startup script
COPY startup.sh /usr/local/bin/startup
RUN chmod +x /usr/local/bin/startup

# Use the startup script as the CMD
CMD ["/bin/bash", "-c", "/usr/local/bin/startup"]

# Expose port 8000
EXPOSE 8000

