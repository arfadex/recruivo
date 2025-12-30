#!/bin/bash
set -e

composer install --no-interaction --prefer-dist
npm install

if [ ! -f .env ]; then
    cp .env.example .env
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
    sed -i 's/DB_PASSWORD=/DB_PASSWORD=root/' .env
    sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/' .env
    sed -i 's/MAIL_HOST=127.0.0.1/MAIL_HOST=mailpit/' .env
    sed -i 's/MAIL_PORT=2525/MAIL_PORT=1025/' .env
    php artisan key:generate
fi

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan migrate --force
php artisan db:seed --force
php artisan storage:link 2>/dev/null || true
npm run build

echo ""
echo "Ready! Demo: admin@recruivo.work / password"
echo ""
