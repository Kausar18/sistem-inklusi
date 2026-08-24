#!/bin/sh
set -e

# Railway/Render menyediakan port dinamis lewat $PORT — Apache secara
# default cuma dengar di port 80, jadi disesuaikan dulu di sini.
if [ -n "$PORT" ]; then
    sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
    sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf
fi

# APP_KEY wajib ada sebelum artisan lain dijalankan.
if [ -z "$APP_KEY" ]; then
    echo "PERINGATAN: APP_KEY belum diset. Set env var APP_KEY di dashboard hosting."
fi

php artisan config:clear
php artisan storage:link || true
php artisan migrate --force

exec "$@"
