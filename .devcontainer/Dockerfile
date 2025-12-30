FROM serversideup/php:8.2-cli

USER root

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    default-mysql-client \
    redis-tools \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions pdo_mysql redis gd zip bcmath pcntl exif opcache

USER www-data
