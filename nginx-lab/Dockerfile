FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl \
    librdkafka-dev \
    libzip-dev \
    && docker-php-ext-install zip

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копируем composer.json и устанавливаем зависимости
COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader

# Копируем приложение
COPY ./www /var/www/html

CMD ["php-fpm"]