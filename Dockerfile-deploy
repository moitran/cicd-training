FROM php:7.4-fpm

USER root

WORKDIR /var/www

# Install dependencies
RUN apt-get update \
    # gd
    && apt-get install -y --no-install-recommends build-essential  openssl nginx libfreetype6-dev libjpeg-dev libpng-dev libwebp-dev zlib1g-dev libzip-dev gcc g++ make vim unzip curl git jpegoptim optipng pngquant gifsicle locales libonig-dev \
    && docker-php-ext-configure gd  \
    && docker-php-ext-install gd \
    # gmp
    && apt-get install -y --no-install-recommends libgmp-dev \
    && docker-php-ext-install gmp \
    # pdo_mysql
    && docker-php-ext-install pdo_mysql mbstring \
    # pdo
    && docker-php-ext-install pdo \
    # opcache
    && docker-php-ext-enable opcache \
    # zip
    && docker-php-ext-install zip \
    && apt-get autoclean -y \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/pear/

# Copy source code
COPY . /var/www

# Copy Nginx config
COPY ./deploy/nginx.conf /etc/nginx/nginx.conf

COPY ./deploy/custom_php.ini /usr/local/etc/php/custom_php.ini

RUN chmod +rwx /var/www

RUN chmod -R 777 /var/www

# setup composer and symfony
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --prefer-dist --no-interaction --working-dir="/var/www"

EXPOSE 80

RUN ["chmod", "+x", "./deploy/deploy.sh"]

CMD [ "sh", "./deploy/deploy.sh" ]
