#!/bin/sh
set -e

# public/ dijalankan dari volume bersama dengan Nginx (supaya Nginx bisa melayani file
# statis langsung tanpa lewat PHP-FPM). Volume itu disegarkan dari isi image setiap kali
# container start, supaya pembaruan aset (CSS/JS/gambar) di kode selalu ikut terpakai
# walau volume-nya sudah ada dari deploy sebelumnya. Folder uploads sengaja TIDAK ikut
# disegarkan karena di-mount terpisah sebagai volume persisten (berkas upload pengguna).
mkdir -p /var/www/public/uploads
rsync -a --delete --exclude uploads /opt/public-src/ /var/www/public/

php artisan config:clear

# Database berada di luar Docker Compose ini (server terpisah). Tunggu sampai bisa
# dihubungi (maksimal ~60 detik) sebelum menjalankan migrasi, supaya container tidak
# langsung gagal kalau start bersamaan saat jaringan/DB belum siap.
attempt=0
until php artisan migrate:status >/dev/null 2>&1; do
    attempt=$((attempt + 1))
    if [ "$attempt" -ge 30 ]; then
        echo "Database tidak bisa dihubungi setelah 30 percobaan, keluar." >&2
        exit 1
    fi
    echo "Menunggu database siap... ($attempt/30)"
    sleep 2
done

php artisan migrate --force
php artisan config:cache
php artisan route:cache

chown -R www-data:www-data storage bootstrap/cache public/uploads

exec "$@"
