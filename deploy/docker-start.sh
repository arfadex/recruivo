#!/bin/bash

# Recruivo - Docker Setup Script
# This script helps you set up the application with Docker

set -e

echo "🚀 Recruivo Docker Setup"
echo "========================"
echo ""

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker Desktop and try again."
    exit 1
fi

# Check if .env file exists
if [ ! -f .env ]; then
    echo "⚠️  No .env file found!"
    echo ""
    echo "📝 Please ensure you have a .env file with your settings."
    echo "   Make sure DB_HOST=mysql (not 127.0.0.1)"
    echo ""
    echo "If this is your first time, copy from .env.example:"
    echo "   cp .env.example .env"
    echo ""
    exit 1
fi

# Build and start containers
echo "🏗️  Building and starting Docker containers..."
docker compose up -d --build

echo ""
echo "⏳ Waiting for MySQL to be ready..."
sleep 10

# Backend .env should already exist from earlier check

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
docker compose exec -T laravel composer install

# Install NPM dependencies
echo "📦 Installing NPM dependencies..."
docker compose exec -T laravel npm install

# Generate application key
echo "🔑 Generating application key..."
docker compose exec -T laravel php artisan key:generate

# Run migrations
echo "🗄️  Running database migrations..."
docker compose exec -T laravel php artisan migrate --seed

# Build assets
echo "🎨 Building frontend assets..."
docker compose exec -T laravel npm run build

echo ""
echo "✅ Setup complete!"
echo ""
echo "📍 Your application is now running at:"
echo "   - laravel: http://localhost:8000"
echo ""
echo "📝 Useful commands:"
echo "   - View logs: docker compose logs -f"
echo "   - Stop containers: docker compose down"
echo "   - Run artisan: docker compose exec laravel php artisan [command]"
echo ""
echo "🎉 Happy coding!"

