FROM php:8.2-fpm

# Instala extensões PHP essenciais e dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql

# Instala o Redis
RUN pecl install redis && docker-php-ext-enable redis

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário para a aplicação
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copiar arquivos da aplicação
COPY . /var/www
COPY --chown=www:www . /var/www

# Mudar para o usuário www
USER www

# Expor porta 9000 e iniciar servidor PHP-FPM
EXPOSE 9000
CMD ["php-fpm"] 