#!/bin/bash
set -e

echo "==> Installing PHP extensions..."
sudo docker-php-ext-install pdo_mysql bcmath pcntl > /dev/null 2>&1 || true

echo "==> Installing PHP dependencies..."
composer install --no-interaction --prefer-dist

echo "==> Installing Node.js dependencies..."
npm install

echo "==> Setting up environment file..."
if [ ! -f .env ]; then
    cp .env.example .env
    
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=127.0.0.1/' .env
    sed -i 's/DB_USERNAME=root/DB_USERNAME=root/' .env
    sed -i 's/DB_PASSWORD=$/DB_PASSWORD=/' .env
    
    echo "REDIS_HOST=127.0.0.1" >> .env
    
    php artisan key:generate
fi

echo "==> Setting up storage directories..."
mkdir -p storage/framework/{cache,sessions,views,testing}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "==> Starting MySQL service..."
mysql.server start 2>/dev/null || sudo service mysql start 2>/dev/null || true

echo "==> Waiting for MySQL to be ready..."
for i in {1..30}; do
    if mysql -u root -e "SELECT 1" 2>/dev/null; then
        echo "MySQL is ready!"
        break
    fi
    echo "Waiting for MySQL... ($i/30)"
    sleep 2
done

echo "==> Creating database..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS recruivo_db;" 2>/dev/null || true

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Seeding database..."
php artisan db:seed --force

echo "==> Creating storage symlink..."
php artisan storage:link 2>/dev/null || true

echo "==> Building frontend assets..."
npm run build

echo ""
echo "============================================"
echo "  Recruivo development environment ready!"
echo "============================================"
echo ""
echo "  Start the Laravel server:"
echo "    php artisan serve"
echo ""
echo "  Start Vite dev server (for HMR):"
echo "    npm run dev"
echo ""
echo "  Demo accounts:"
echo "    Admin:     admin@recruivo.work / password"
echo "    Recruiter: recruiter@recruivo.work / password"
echo "    Candidate: candidate@recruivo.work / password"
echo ""
echo "============================================"
