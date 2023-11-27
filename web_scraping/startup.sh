#!/bin/bash

# Wait for MySQL to be ready
wait-for-it mysql:3306

# Run Laravel migrations
php artisan migrate --force

# Start the Laravel application
php artisan serve --host=0.0.0.0 --port=8000
