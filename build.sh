#!/bin/bash
set -e

if [ ! -f .env ]; then
    echo "Error: .env file not found. Create it from .env.example first."
    exit 1
fi

echo "Build docker compose"
docker compose build

echo "Up docker compose"
docker compose up -d

echo "Installing PHP dependencies..."
docker compose exec -T www composer install --no-interaction

echo "Generate secret key"
docker compose exec -T www php artisan key:generate

echo "Installing Node dependencies..."
docker compose exec -T www npm ci

echo "Building frontend..."
docker compose exec -T www npm run build

echo "Running migrations..."
docker compose exec -T www php artisan migrate --force

echo "Caching..."
docker compose exec -T www php artisan optimize:clear
docker compose exec -T www php artisan config:cache
docker compose exec -T www php artisan route:cache
docker compose exec -T www php artisan view:cache

echo "Restarting workers..."
docker compose restart horizon scheduler www

echo "✅ Build complete! Open http://localhost:8080/"
