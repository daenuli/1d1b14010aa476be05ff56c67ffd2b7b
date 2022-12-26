FROM php:8.1-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev supervisor && \
    pecl install apcu && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql && \
    # docker-php-ext-enable apcu pdo_pgsql sodium && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY start.sh /usr/local/bin/start

RUN chown -R $user: /var/www/html && chmod u+x /usr/local/bin/start

COPY supervisord.conf /etc/supervisor/supervisord.conf

RUN mkdir -p "/etc/supervisor/logs"

CMD ["/usr/local/bin/start"]