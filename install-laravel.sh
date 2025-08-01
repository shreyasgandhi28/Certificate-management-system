#!/bin/bash

# Build and start containers
docker-compose up --build -d

# Wait for containers to be ready
echo "Waiting for containers to be ready..."
sleep 30

# Install Laravel
docker-compose exec app composer create-project --prefer-dist laravel/laravel .

# Set permissions
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 755 /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/bootstrap/cache

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate

echo "Laravel installation completed!"
echo "Access your application at http://localhost:8080"
echo "Access Mailcatcher at http://localhost:1080"
