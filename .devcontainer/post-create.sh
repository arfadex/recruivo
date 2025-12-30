#!/bin/bash
set -e

echo ""
echo "============================================"
echo "  Setting up Recruivo Development Environment"
echo "============================================"
echo ""

# Install PHP extensions
echo "==> Installing PHP extensions..."
sudo apt-get update -qq
sudo apt-get install -y -qq libpng-dev libonig-dev libxml2-dev libzip-dev libfreetype6-dev libjpeg62-turbo-dev > /dev/null 2>&1

sudo docker-php-ext-configure gd --with-freetype --with-jpeg > /dev/null 2>&1
sudo docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath zip gd opcache > /dev/null 2>&1

# Install Redis extension
echo "==> Installing Redis extension..."
sudo pecl install redis > /dev/null 2>&1 || true
sudo docker-php-ext-enable redis > /dev/null 2>&1 || true

# Install Composer dependencies
echo "==> Installing PHP dependencies..."
composer install --no-interaction --prefer-dist

# Install Node dependencies
echo "==> Installing Node.js dependencies..."
npm install

# Setup environment file
echo "==> Setting up environment file..."
if [ ! -f .env ]; then
    cp .env.example .env
    
    # Configure for Codespaces
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
    sed -i 's/DB_USERNAME=root/DB_USERNAME=root/' .env
    sed -i 's/DB_PASSWORD=/DB_PASSWORD=root/' .env
    
    # Redis configuration
    sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/' .env
    sed -i 's/CACHE_STORE=database/CACHE_STORE=redis/' .env
    sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=redis/' .env
    sed -i 's/QUEUE_CONNECTION=database/QUEUE_CONNECTION=redis/' .env
    
    # Mailpit configuration
    sed -i 's/MAIL_MAILER=log/MAIL_MAILER=smtp/' .env
    sed -i 's/MAIL_HOST=127.0.0.1/MAIL_HOST=mailpit/' .env
    sed -i 's/MAIL_PORT=2525/MAIL_PORT=1025/' .env
    
    php artisan key:generate
fi

# Setup storage directories
echo "==> Setting up storage directories..."
mkdir -p storage/framework/{cache,sessions,views,testing}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Wait for MySQL (health check should handle this, but belt-and-suspenders)
echo "==> Verifying MySQL connection..."
for i in {1..30}; do
    if mysqladmin ping -h mysql -u root -proot --silent 2>/dev/null; then
        echo "    MySQL is ready!"
        break
    fi
    if [ $i -eq 30 ]; then
        echo "    Warning: MySQL may not be ready, continuing anyway..."
    fi
    sleep 2
done

# Wait for Redis
echo "==> Verifying Redis connection..."
for i in {1..30}; do
    if redis-cli -h redis ping 2>/dev/null | grep -q PONG; then
        echo "    Redis is ready!"
        break
    fi
    if [ $i -eq 30 ]; then
        echo "    Warning: Redis may not be ready, continuing anyway..."
    fi
    sleep 2
done

# Run migrations
echo "==> Running database migrations..."
php artisan migrate --force

# Seed database
echo "==> Seeding database..."
php artisan db:seed --force

# Create storage symlink
echo "==> Creating storage symlink..."
php artisan storage:link 2>/dev/null || true

# Build frontend assets
echo "==> Building frontend assets..."
npm run build

echo ""
echo "============================================"
echo "  Recruivo is ready!"
echo "============================================"
echo ""
echo "  Start the development server:"
echo "    php artisan serve"
echo ""
echo "  Or with Vite hot reload:"
echo "    npm run dev"
echo ""
echo "  Services:"
echo "    App:     http://localhost:8000"
echo "    Vite:    http://localhost:5173"
echo "    Mailpit: http://localhost:8025"
echo ""
echo "  Demo accounts:"
echo "    Admin:     admin@recruivo.work / password"
echo "    Recruiter: recruiter@recruivo.work / password"
echo "    Candidate: candidate@recruivo.work / password"
echo ""
echo "============================================"
