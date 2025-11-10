# Base PHP CLI
FROM php:8.2-cli

# Evitar interacción en la instalación
ENV DEBIAN_FRONTEND=noninteractive

# Instalar extensiones y dependencias necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    npm \
    nodejs \
    && docker-php-ext-install intl pdo pdo_pgsql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar el código
COPY . .

# Configurar Git para evitar "dubious ownership"
RUN git config --global --add safe.directory /var/www/html

# Instalar dependencias de PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Exponer puerto para Laravel
EXPOSE 8000
