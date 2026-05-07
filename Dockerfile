# Stage 1: Build frontend assets
FROM node:22-alpine AS frontend
WORKDIR /app

# Copy package files and install npm dependencies
COPY package*.json ./
RUN npm ci

# Copy remaining source and build assets
COPY . .
RUN npm run build

# Stage 2: Production PHP-FPM + NGINX server
FROM serversideup/php:8.3-fpm-nginx

WORKDIR /var/www/html

# Copy application code
COPY --chown=www-data:www-data . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy the built frontend assets from the frontend stage
COPY --from=frontend /app/public/build /var/www/html/public/build

# Set Laravel permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80