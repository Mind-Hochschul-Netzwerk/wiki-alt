FROM trafex/php-nginx:3.5.0

LABEL Maintainer="Henrik Gebauer <code@henrik-gebauer.de>" \
      Description="mind-hochschul-netzwerk.de"

HEALTHCHECK --interval=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping

USER root

# apply (security) updates
RUN set -x \
  && apk upgrade --no-cache

# workaround for iconv issue
RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/v3.13/community/ gnu-libiconv==1.15-r3
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# install packages
RUN set -ex \
  && apk --no-cache add \
    php83-zip \
    php83-pdo_mysql \
    php83-iconv \
    php83-simplexml \
    php83-tokenizer \
    php83-soap \
    php83-fileinfo \
    php83-pdo \
    imagemagick \
  && chown nobody:nobody /var/www

COPY --chown=nobody docker/assets/ docker/get-resources.sh docker/resources.list /tmp/build/

COPY config/nginx/ /etc/nginx
COPY --chown=nobody app/ /var/www

RUN set -ex \
  && /tmp/build/get-resources.sh \
  && tar --strip-components=1 -C /var/www/html -xzf /tmp/build/mediawiki-*.tar.gz \
  && rm -rf /tmp/build \
  && echo -e "[program:wiki-maintenance]\ncommand=php /var/www/html/maintenance/update.php\nautorestart=false" >> /etc/supervisor/conf.d/supervisord.conf

USER nobody

VOLUME /var/www/html/images
