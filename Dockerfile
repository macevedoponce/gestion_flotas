# 🐘 Imagen base PHP con extensiones necesarias
FROM php:8.2-cli

# Evita prompts interactivos
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependencias del sistema, PHP y Node
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    nodejs \
    npm \
    && docker-php-ext-install intl pdo pdo_pgsql zip

# Instalar Composer (desde imagen oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar solo los archivos de Composer para aprovechar la caché
COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist || true

# ⚠️ No copiamos todo el código aquí porque el volumen ya lo montará
# COPY . .

# Exponer puerto para el servidor de desarrollo
EXPOSE 8000

# Comando por defecto (espera a que el contenedor arranque correctamente)
CMD php artisan serve --host=0.0.0.0 --port=8000
