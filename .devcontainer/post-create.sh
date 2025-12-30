#!/bin/bash
set -e

echo ""
echo "============================================"
echo "  Setting up Recruivo Development Environment"
echo "============================================"
echo ""

composer install --no-interaction --prefer-dist

npm install

if [ ! -f .env ]; then
    cp .env.example .env
    
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
    sed -i 's/DB_USERNAME=root/DB_USERNAME=root/' .env
    sed -i 's/DB_PASSWORD=/DB_PASSWORD=root/' .env
    
    sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/' .env
    sed -i 's/CACHE_STORE=database/CACHE_STORE=redis/' .env
    sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=redis/' .env
    sed -i 's/QUEUE_CONNECTION=database/QUEUE_CONNECTION=redis/' .env
    
    sed -i 's/MAIL_MAILER=log/MAIL_MAILER=smtp/' .env
    sed -i 's/MAIL_HOST=127.0.0.1/MAIL_HOST=mailpit/' .env
    sed -i 's/MAIL_PORT=2525/MAIL_PORT=1025/' .env
    
    php artisan key:generate
fi

mkdir -p storage/framework/{cache,sessions,views,testing}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "==> Waiting for MySQL..."
for i in {1..30}; do
    if mysqladmin ping -h mysql -u root -proot --silent 2>/dev/null; then
        echo "    MySQL ready!"
        break
    fi
    sleep 2
done

echo "==> Waiting for Redis..."
for i in {1..30}; do
    if redis-cli -h redis ping 2>/dev/null | grep -q PONG; then
        echo "    Redis ready!"
        break
    fi
    sleep 2
done

php artisan migrate --force
php artisan db:seed --force
php artisan storage:link 2>/dev/null || true

npm run build

echo ""
echo "============================================"
echo "  Recruivo is ready!"
echo "============================================"
echo ""
echo "  Run: php artisan serve"
echo ""
echo "  Demo accounts:"
echo "    admin@recruivo.work / password"
echo "    recruiter@recruivo.work / password"
echo "    candidate@recruivo.work / password"
echo ""
