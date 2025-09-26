#!/bin/sh

# Exit on any error
set -e

echo "Starting Laravel application setup..."

# Wait for database connection if needed
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database connection..."
    while ! nc -z "$DB_HOST" "${DB_PORT:-3306}"; do
        sleep 1
    done
    echo "Database is ready!"
fi

# Generate application key if it doesn't exist
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --no-interaction
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migrations may have already been run, continuing..."

# Create Laravel storage directories
echo "Creating Laravel storage directories..."
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/framework/cache

# Run Passport migrations (ignore if tables already exist)
echo "Running Passport migrations..."
php artisan migrate --path=vendor/laravel/passport/database/migrations --force 2>/dev/null || echo "Passport tables already exist, continuing..."

# Generate Passport keys if they don't exist
if [ ! -f /var/www/storage/oauth-private.key ]; then
    echo "Installing Passport..."
    php artisan passport:install --force --no-interaction || echo "Passport installation failed, continuing..."
fi

# Clear and cache configuration
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache

# Set proper permissions (run as root)
if [ "$(id -u)" = "0" ]; then
    chown -R www:www /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
fi

# Create supervisor log directory
mkdir -p /var/log/supervisor

echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf