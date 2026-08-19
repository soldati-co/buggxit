# Stage 1: Build frontend assets
FROM node:22-alpine AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# Stage 2: Production PHP-FPM + NGINX server
FROM serversideup/php:8.4-fpm-nginx

WORKDIR /var/www/html

# GD (image resizing/encoding for hero slides) and exif (JPEG auto-orientation)
# aren't included by default on this base image. serversideup/php images run
# unprivileged by default, so extensions must be installed as root, then the
# user switched back to www-data.
USER root
RUN install-php-extensions gd exif
USER www-data

# Copy application code
COPY --chown=www-data:www-data . /var/www/html

# Remove any stale cached bootstrap artifacts from the source before installing
RUN rm -f bootstrap/cache/config.php bootstrap/cache/routes-v7.php bootstrap/cache/packages.php bootstrap/cache/services.php bootstrap/cache/events.php

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy the built frontend assets from the frontend stage
COPY --from=frontend /app/public/build /var/www/html/public/build

# Set Laravel permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80