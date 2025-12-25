#!/bin/bash
set -e

echo "==> Installing PHP dependencies..."
composer install --no-interaction --prefer-dist

echo "==> Installing Node.js dependencies..."
npm install

echo "==> Setting up environment file..."
if [ ! -f .env ]; then
    cp .env.example .env
    
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
    sed -i 's/DB_USERNAME=root/DB_USERNAME=recruivo/' .env
    sed -i 's/DB_PASSWORD=/DB_PASSWORD=password/' .env
    
    echo "REDIS_HOST=redis" >> .env
    
    php artisan key:generate
fi

echo "==> Setting up storage directories..."
mkdir -p storage/framework/{cache,sessions,views,testing}
mkdir -p storage/logs
mkdir -p bootstrap/cache

echo "==> Waiting for MySQL to be ready..."
until mysql -h mysql -u recruivo -ppassword -e "SELECT 1" > /dev/null 2>&1; do
    echo "Waiting for MySQL..."
    sleep 2
done

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
echo "    php artisan serve --host=0.0.0.0 --port=8000"
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
