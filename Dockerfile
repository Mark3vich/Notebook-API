# Указываем базовый образ PHP с поддержкой Composer
FROM php:8.2-fpm

# Устанавливаем необходимые зависимости
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql zip

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Копируем приложение внутрь контейнера
WORKDIR /var/www
COPY . .

# Установка прав
RUN chown -R www-data:www-data /var/www

# Устанавливаем зависимости Laravel
RUN composer install --no-dev --optimize-autoloader

# Настраиваем права доступа для каталога хранения
RUN chmod -R 775 storage bootstrap/cache

# Указываем порт
EXPOSE 9000

CMD ["php-fpm"]
