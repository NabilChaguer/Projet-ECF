FROM php:8.2-cli

# Dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libsqlite3-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Autoriser les plugins Composer en root
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# Installer les dépendances PHP (sans scripts artisan)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copier le reste de l'application
COPY . .

# Préparer la base SQLite + permissions Laravel
RUN mkdir -p database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database

# Variables par défaut
ENV APP_ENV=production
ENV APP_DEBUG=false

# Port HTTP utilisé par Fly
EXPOSE 8080

# Lancer le serveur PHP intégré en servant le dossier public/
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
