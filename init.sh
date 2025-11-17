#!/bin/bash

echo "🚀 Iniciando entorno de desarrollo Laravel + Docker..."

# 1. Apagar contenedores anteriores
docker compose down --volumes --remove-orphans

# 2. Reconstruir contenedores
docker compose up -d --build

echo "⏳ Esperando a que los servicios arranquen..."
sleep 10

# 3. Ejecutar instalación dentro de app
docker compose exec app bash -c "
    git config --global --add safe.directory /var/www/html

    if [ ! -f .env ]; then
      cp .env.example .env
    fi

    composer install
    npm install
    npm run build

    php artisan key:generate
    php artisan storage:link

    php artisan migrate --seed || true

    chown -R www-data:www-data storage bootstrap/cache
"

echo "🎯 Proyecto iniciado correctamente en: http://localhost:8000"
