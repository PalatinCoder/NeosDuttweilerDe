FROM php:7.2-fpm-alpine AS base

# add s6-overlay as supervisor
ADD https://github.com/just-containers/s6-overlay/releases/latest/download/s6-overlay-amd64.tar.gz /tmp/s6overlay.tar.gz
RUN tar xzf /tmp/s6overlay.tar.gz -C / && rm /tmp/s6overlay.tar.gz

# install nginx as frontend for fpm
RUN apk add nginx \
    && echo -e "daemon off;\npid /var/run/nginx.pid;" >> /etc/nginx/nginx.conf \
    # redirect nginx logs
    && ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

# define run scripts
RUN mkdir /etc/services.d/nginx && mkdir /etc/services.d/php-fpm \
    && echo -e "#!/usr/bin/with-contenv sh\nnginx" > /etc/services.d/nginx/run \
    && echo -e "#!/usr/bin/with-contenv sh\nphp-fpm" > /etc/services.d/php-fpm/run \
    && echo -e "#!/usr/bin/execlineb -S1\nif { s6-test \${1} -ne 0 }\nif { s6-test \${1} -ne 256 }\ns6-svscanctl -t /var/run/s6/services" | tee /etc/services.d/nginx/finish > /etc/services.d/php-fpm/finish

# define init script for neos
RUN echo -e "#!/usr/bin/with-contenv sh\n/neos/flow doctrine:migrate\n/neos/flow resource:publish" > /etc/cont-init.d/flow

# install php extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
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

# adjust php settings (as late as possible so we can make use of build caching before)
RUN echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini && \
    echo 'expose_php = 0' >> /usr/local/etc/php/conf.d/docker-php-expose.ini && \
    # set fpm log format so it matches nginx
    echo 'access.format = "%R - - [%t] \"%m %r %q\" %s %{X-Forwarded-for}e"' >> /usr/local/etc/php-fpm.d/www-logformat.conf

ARG context
RUN mv "$PHP_INI_DIR/php.ini-${context}" "$PHP_INI_DIR/php.ini"

# copy distribution
WORKDIR /neos
COPY . .
RUN composer install --no-dev
# move nginx config file to where it belongs
RUN mv nginx-fpm.conf /etc/nginx/conf.d/default.conf

ENTRYPOINT [ "/init" ]