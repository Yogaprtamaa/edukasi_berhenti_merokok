#!/usr/bin/env bash
#
# Jalankan aplikasi dengan PHP 8.2 (WAJIB).
#
# Project ini memakai Laravel 9 + mongodb/laravel-mongodb 3.9 yang HANYA
# kompatibel dengan PHP <= 8.3 dan ext-mongodb 1.x.
# PHP default mesin ini (8.4 + ext-mongodb 2.x) AKAN error fatal:
#   "Expected integer or object, string given"
# pada setiap penyimpanan data bertimestamp.
#
# Pakai:  ./serve.sh            -> jalankan server di http://127.0.0.1:8000
#         ./serve.sh artisan migrate   -> jalankan artisan apa pun dgn php 8.2
#         ./serve.sh composer install  -> jalankan composer dgn php 8.2

set -e

PHP82="/opt/homebrew/opt/php@8.2/bin/php"

# Naikkan limit upload lewat .phpini/99-uploads.ini tanpa mengubah php.ini sistem.
# Dipakai PHP_INI_SCAN_DIR (bukan flag -d) karena `artisan serve` mem-fork proses
# `php -S` terpisah: flag -d tidak diwariskan, environment variable diwariskan.
# Titik dua di depan = tetap muat direktori conf.d bawaan (ext-mongodb dll).
export PHP_INI_SCAN_DIR=":$(cd "$(dirname "$0")" && pwd)/.phpini"

if [ ! -x "$PHP82" ]; then
  echo "PHP 8.2 tidak ditemukan di $PHP82. Install dengan: brew install php@8.2" >&2
  exit 1
fi

cd "$(dirname "$0")"

if [ "$1" = "artisan" ]; then
  shift
  exec "$PHP82" artisan "$@"
elif [ "$1" = "composer" ]; then
  shift
  exec "$PHP82" "$(command -v composer)" "$@"
else
  exec "$PHP82" artisan serve "$@"
fi
