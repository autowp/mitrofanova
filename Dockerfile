FROM ubuntu:bionic

LABEL maintainer "dmitry@pereslegin.ru"

WORKDIR /app

EXPOSE 80

ENV COMPOSER_ALLOW_SUPERUSER 1

VOLUME /app/logs
VOLUME /app/data

RUN DEBIAN_FRONTEND=noninteractive apt-get autoremove -qq -y && \
    DEBIAN_FRONTEND=noninteractive apt-get update -qq -y && \
    DEBIAN_FRONTEND=noninteractive apt-get dist-upgrade -qq -y \
    && \
    DEBIAN_FRONTEND=noninteractive apt-get install -qq -y \
        ca-certificates \
        composer \
        nginx \
        php \
        php-fpm \
        php-json \
        php-mbstring \
        php-sqlite3 \
        php-opcache \
        php-pdo \
        php-xml \
        sqlite3 \
        supervisor \
    && \
    DEBIAN_FRONTEND=noninteractive apt-get autoclean -qq -y

COPY ./etc/ /etc/

COPY composer.json /app/composer.json
RUN composer install --no-dev --no-progress --no-interaction --no-suggest --optimize-autoloader && \
    composer clearcache

COPY . /app

RUN chmod +x start.sh

CMD ["./start.sh"]
