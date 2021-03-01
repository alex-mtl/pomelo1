FROM php:7.4-fpm-alpine

RUN apk update \
    && apk upgrade \
    && apk add bash \
    && docker-php-ext-install pdo_mysql \
    && apk add --update --no-cache g++ gcc libxslt-dev \
    && docker-php-ext-install soap \
    && apk add git \
    && apk add --no-cache zip libzip-dev \
    && docker-php-ext-install zip \
    && apk add openssh

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install xdebug-2.9.8 \
  && docker-php-ext-enable xdebug \
  && pecl install -o -f redis \
  && rm -rf /tmp/pear \
  && docker-php-ext-enable redis \
  && apk del pcre-dev ${PHPIZE_DEPS}

RUN apk add --no-cache libpng libpng-dev \
  && docker-php-ext-install gd \
  && apk del libpng-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./conf.d/10-xdebug.ini /usr/local/etc/php/conf.d/10-xdebug.ini

WORKDIR /var/www/pomelo
