FROM php:7.2-cli-alpine AS base
ARG context=production
RUN mv "$PHP_INI_DIR/php.ini-${context}" "$PHP_INI_DIR/php.ini"
RUN echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

# install prereqs
RUN install-php-extensions pdo_mysql redis imagick
# build and enable vips manually, not yet supported by the script
RUN apk --no-cache add vips \
    && apk --no-cache add --virtual .vips-build vips-dev ${PHPIZE_DEPS} \
    && pecl install vips \
    && docker-php-ext-enable vips \
    && apk del .vips-build

# create base distribution (so it can be cached)
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN composer create-project neos/neos-base-distribution:~3.3.0 /neos --no-dev

# copy distribution
FROM base AS dist
WORKDIR /neos
COPY . .
RUN composer install --no-dev

# run dev server
FROM dist AS dev
EXPOSE 8081
CMD [ "./flow", "server:run", "--host", "0.0.0.0" ]
