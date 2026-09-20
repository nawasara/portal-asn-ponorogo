#!/bin/sh
set -e

# Langkah inisialisasi hanya untuk proses utama (php-fpm), bukan untuk
# perintah lain seperti queue worker atau scheduler.
if [ "$1" = "php-fpm" ]; then
    echo "==> Menyiapkan aplikasi..."

    # Sinkronkan public/ ke volume bersama supaya memakai aset hasil build.
    if [ -d /var/www/html/public-dist ]; then
        cp -R /var/www/html/public-dist/. /var/www/html/public/
    fi

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link || true

    # Migrasi nonaktif secara bawaan supaya deploy tidak pernah mengubah skema
    # produksi tanpa disengaja. Aktifkan dengan RUN_MIGRATIONS=true, atau:
    #   docker compose -f deploy/docker-compose.yml exec app php artisan migrate --force
    if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
        echo "==> Menjalankan migrasi database..."
        php artisan migrate --force
    else
        echo "==> Migrasi dilewati (RUN_MIGRATIONS!=true)."
    fi

    echo "==> Inisialisasi selesai."
fi

exec "$@"
