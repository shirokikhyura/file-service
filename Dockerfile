FROM shirokikh/php-7.4:fpm AS base

WORKDIR /var/www/file-service
COPY docker/php-ini-overrides.ini /etc/php/7.4/fpm/conf.d/99-overrides.ini

FROM base AS development
RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        php7.4-xdebug; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
COPY docker/99-xdebug-overrides.ini /etc/php/7.4/fpm/conf.d/99-xdebug-overrides.ini
COPY docker/99-xdebug-overrides.ini /etc/php/7.4/cli/conf.d/99-xdebug-overrides.ini
EXPOSE 9009

FROM base AS build

COPY . .
ENV APP_ENV=prod
RUN chown -R www-data:www-data /var/www/file-service
RUN composer install --no-interaction

FROM build AS independent
CMD php -S 0.0.0.0:80 public/index.php

FROM build AS fpm
CMD ["/usr/sbin/php-fpm7.4", "-O" ]
EXPOSE 9000