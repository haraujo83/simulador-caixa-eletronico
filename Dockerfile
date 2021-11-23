FROM ambientum/php:7.4-nginx

USER root

RUN rm /etc/apk/repositories
ADD https://packages.whatwedo.ch/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub
RUN echo "https://packages.whatwedo.ch/php-alpine/v3.11/php-7.4" >> /etc/apk/repositories

COPY ./start.sh /usr/local/bin/start
RUN chmod u+x /usr/local/bin/start

RUN apk update && \
    apk add php7-dev@php --force-broken-world && \
	apk add php7-pear@php --force-broken-world && \
    apk add php7-gmp@php --force-broken-world && \
    apk add autoconf --force-broken-world && \
    apk add openssl --force-broken-world && \
    apk add --no-cache tzdata --force-broken-world && \
    pecl channel-update pecl.php.net && \
    pear config-set php_ini /etc/php7/php.ini

ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

USER ambientum

WORKDIR /var/www/app

CMD ["/usr/local/bin/start"]
