# Usar la imagen base oficial de PHP 8.2 con FPM
FROM php:8.2-fpm

# Argumentos definidos en el docker-compose.yml
ARG user=defaultuser
ARG uid=1000

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    wget \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libxpm-dev \
    golang-go \
    whatweb \
    gobuster && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm && \
    docker-php-ext-install gd pdo_pgsql pdo_mysql mbstring exif pcntl bcmath && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Gobuster vía Go
RUN go install github.com/OJ/gobuster/v3@latest

# Asignar permisos al ejecutable de WhatWeb
RUN chmod +x /usr/bin/whatweb

# Configurar el resolv.conf para DNS
RUN echo '#!/bin/sh\n\
echo "nameserver 127.0.0.1" > /etc/resolv.conf\n\
echo "nameserver 8.8.8.8" >> /etc/resolv.conf\n\
echo "nameserver 8.8.4.4" >> /etc/resolv.conf\n\
echo "nameserver 1.1.1.1" >> /etc/resolv.conf' > /tmp/update-resolv-conf.sh

# Copiar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario y configurar permisos
RUN echo "User: $user, UID: $uid" && \
    useradd -G www-data,root -u $uid -d /home/$user $user && \
    mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Definir directorio de trabajo
WORKDIR /var/www

# Crear carpeta adicional para whatweb
RUN mkdir -p /var/www/html/whatweb

# Cambiar al usuario no root
USER $user

# Exponer el puerto predeterminado de PHP-FPM
EXPOSE 9000

# Comando por defecto
CMD ["php-fpm"]