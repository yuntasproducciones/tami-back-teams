FROM php:8.2-cli-alpine

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias del sistema y Node.js desde el repositorio oficial
RUN apk add --no-cache \
    git \
    mysql-client \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    nodejs \
    npm \
    sqlite-dev

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql

# Copiar la aplicación
RUN mkdir /app
WORKDIR /app