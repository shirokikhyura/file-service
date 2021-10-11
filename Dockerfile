FROM shirokikh/php-7.4:fpm

WORKDIR /var/www/file-service

RUN apt-get update && apt-get -y --no-install-recommends install wget \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
# Get symfony binary only for symfony server
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony
COPY php-ini-overrides.ini /etc/php/7.4/fpm/conf.d/99-overrides.ini
COPY . .
RUN composer install --no-interaction
RUN chown -R www-data:www-data /var/www/file-service

CMD symfony server:start --port=80 --no-tls