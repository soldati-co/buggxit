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

# Copy application code
COPY --chown=www-data:www-data . /var/www/html

# Install PHP dependencies (nightwatch-agent is included)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy the built frontend assets from the frontend stage
COPY --from=frontend /app/public/build /var/www/html/public/build

# Add startup script for Nightwatch agent
RUN echo '#!/bin/sh\nphp /var/www/html/artisan nightwatch:agent > /dev/null 2>&1 &' > /usr/local/bin/start-nightwatch-agent.sh \
    && chmod +x /usr/local/bin/start-nightwatch-agent.sh

# Set Laravel permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80