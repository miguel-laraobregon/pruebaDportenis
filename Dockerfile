FROM php:8.2-apache

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Habilita el módulo de Apache para .htaccess
RUN a2enmod rewrite

# Linux Library
RUN apt-get update -y && apt-get install -y \
    unzip zip \
    curl \
    git \
    nano \
    vim \
    libicu-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*


# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# PHP Extension
RUN docker-php-ext-install gettext intl zip pdo pdo_mysql gd


# Copia el archivo de configuración de Apache
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf