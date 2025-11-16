# ============================================================
# 🚀 PHP 8.2 + Composer + Node.js + Extensiones requeridas
# ============================================================
FROM php:8.2-cli

ENV DEBIAN_FRONTEND=noninteractive

# ------------------------------------------------------------
# Dependencias del sistema y extensiones PHP importantes
# ------------------------------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    nodejs \
    npm \
    zip \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install intl gd pdo pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# ------------------------------------------------------------
# Composer del contenedor oficial
# ------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ------------------------------------------------------------
# Directorio de trabajo
# ------------------------------------------------------------
WORKDIR /var/www/html

# ------------------------------------------------------------
# Copia mínima para aprovechar cache
# ------------------------------------------------------------
COPY composer.json composer.lock* package*.json* ./

RUN composer install --no-interaction --prefer-dist || true
RUN npm install || true && npm run build || true

# ------------------------------------------------------------
# Fix automático de storage y permisos durante el build
# ------------------------------------------------------------
RUN mkdir -p /var/www/html/storage/app/livewire-tmp \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/public

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
