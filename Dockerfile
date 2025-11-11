# 🐘 Imagen base PHP con Node.js y cliente Postgres
FROM php:8.2-cli

# Evita prompts interactivos
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependencias del sistema y PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    nodejs \
    npm \
    && docker-php-ext-install intl pdo pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer desde imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos mínimos para aprovechar caché en instalación
COPY composer.json composer.lock* package*.json* ./

# Instalar dependencias PHP y JS (en build)
RUN composer install --no-interaction --prefer-dist || true
RUN npm install || true && npm run build || true

# Exponer puerto del servidor Laravel
EXPOSE 8000

# Comando por defecto (puede ser sobrescrito por docker-compose)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
