# ----------------------------------------
# STAGE 1 : Build des assets avec Vite
# ----------------------------------------
FROM node:20 AS vite

WORKDIR /app

# Installer dépendances npm
COPY package.json package-lock.json ./
RUN npm install

# Copier sources nécessaires pour le build Vite
COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

# Compiler les assets
RUN npm run build

# ----------------------------------------
# STAGE 2 : Image PHP pour Laravel
# ----------------------------------------
FROM php:8.2-cli

# Install packages système + extensions PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Répertoire de Laravel
WORKDIR /var/www/html

# Installer dépendances PHP
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copier tout le code Laravel
COPY . .

# Copier les assets compilés depuis le stage Node
COPY --from=vite /app/public/build ./public/build

# ----------------------------------------
# FIX : Créer les dossiers storage manquants + permissions correctes
# ----------------------------------------
RUN mkdir -p storage/framework \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/cache \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Base SQLite (au cas où)
RUN mkdir -p database && touch database/database.sqlite

# Port exposé
EXPOSE 8080

# Commande de démarrage
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]