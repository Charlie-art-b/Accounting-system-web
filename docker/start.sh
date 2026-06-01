#!/bin/sh
set -e

cd /var/www/html

# Crear .env desde variables de entorno si no existe
if [ ! -f .env ]; then
    echo "APP_NAME=${APP_NAME:-AccountingSystem}" > .env
    echo "APP_ENV=${APP_ENV:-production}" >> .env
    echo "APP_DEBUG=${APP_DEBUG:-false}" >> .env
    echo "APP_KEY=${APP_KEY:-}" >> .env
    echo "APP_URL=${APP_URL:-http://localhost}" >> .env
    echo "" >> .env
    echo "LOG_CHANNEL=stderr" >> .env
    echo "LOG_LEVEL=error" >> .env
    echo "" >> .env
    echo "DB_CONNECTION=${DB_CONNECTION:-sqlite}" >> .env
    echo "DB_DATABASE=${DB_DATABASE:-/var/www/html/database/database.sqlite}" >> .env
    echo "" >> .env
    echo "CACHE_DRIVER=${CACHE_DRIVER:-file}" >> .env
    echo "SESSION_DRIVER=${SESSION_DRIVER:-file}" >> .env
    echo "SESSION_LIFETIME=${SESSION_LIFETIME:-120}" >> .env
    echo "QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}" >> .env
    echo "FILESYSTEM_DISK=${FILESYSTEM_DISK:-local}" >> .env
fi

# Asegurarse que el archivo SQLite existe
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database

# Generar app key si está vacío
php artisan key:generate --force

# Limpiar y optimizar caché
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migraciones
php artisan migrate --force --seed

# Storage link
php artisan storage:link || true

# Iniciar servicios
exec supervisord -c /etc/supervisord.conf
