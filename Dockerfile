# Multi-stage build for Laravel API with optimized asset building
FROM node:18-alpine AS node-builder

# Set working directory for Node.js build
WORKDIR /app

# Copy package files
COPY sms_app/api/package.json sms_app/api/package-lock.json* ./

# Install Node.js dependencies (including dev dependencies for build)
RUN npm ci

# Copy source files for asset building
COPY sms_app/api/ .

# Build production assets
RUN npm run build

# PHP Application Stage
FROM php:8.2-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    mysql-client \
    netcat-openbsd \
    dcron

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# Install OPcache configuration for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www

# Set working directory
WORKDIR /var/www

# Copy composer files
COPY sms_app/api/composer.json sms_app/api/composer.lock ./

# Install PHP dependencies (production optimized)
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader --no-interaction

# Copy application code (excluding node_modules and build artifacts)
COPY --chown=www:www sms_app/api/ .

# Copy documentation files from parent directory to public docs
COPY --chown=www:www README.md DOC_INDEX.md QUICK_START_GUIDE.md TROUBLESHOOTING_GUIDE.md ADVANCED_CONFIGURATION.md K8S_DEPLOYMENT_GUIDE.md KUBECONFIG_SETUP_README.md docs ./public/docs/

# Copy built assets from node-builder stage
COPY --from=node-builder --chown=www:www /app/public/build ./public/build

# Generate optimized autoloader
RUN git config --global --add safe.directory /var/www && composer dump-autoload --optimize

# Set proper permissions
RUN chown -R www:www /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache \
    && chmod -R 755 /var/www/public

# Create necessary directories
RUN mkdir -p /var/www/storage/logs \
    /var/www/storage/framework/cache \
    /var/www/storage/framework/sessions \
    /var/www/storage/framework/views \
    && chown -R www:www /var/www/storage

# Create nginx configuration
COPY sms_app/api/docker/nginx.conf /etc/nginx/nginx.conf
COPY sms_app/api/docker/default.conf /etc/nginx/http.d/default.conf

# Create supervisor configuration
COPY sms_app/api/docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create PHP-FPM configuration
COPY sms_app/api/docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Copy entrypoint script
COPY sms_app/api/docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Add health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/gateway/health || exit 1

# Expose port
EXPOSE 80

# Use entrypoint (will handle user switching internally)
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]