#!/bin/sh
set -e

cd /var/www/html

# Generar app key si no existe
php artisan key:generate --force

# Limpiar y optimizar caché
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones con seed
php artisan migrate --force --seed

# Crear symlink de storage
php artisan storage:link || true

# Iniciar servicios con Supervisor
exec supervisord -c /etc/supervisord.conf
