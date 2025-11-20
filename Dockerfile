# Stage 1 : Builder assets (Node + Vite)
FROM node:20 AS vite

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

RUN npm run build


# Stage 2 : PHP image
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

# --> COPY DU BUILD VITE depuis le stage Vite
COPY --from=vite /app/public/build ./public/build

RUN mkdir -p database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]

