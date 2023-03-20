FROM php:7.4-fpm

ADD ./php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN addgroup --gid 1000 laravel && adduser laravel --gid 1000 --shell /bin/sh --disabled-password

RUN apt-get update \
  && apt-get install --no-install-recommends -y ca-certificates curl git nano \
  && rm -rf /var/lib/apt/lists/* \
  && curl -Lsf 'https://storage.googleapis.com/golang/go1.8.3.linux-amd64.tar.gz' | tar -C '/usr/local' -xvzf -

ENV PATH /usr/local/go/bin:$PATH
RUN go get github.com/mailhog/mhsendmail \
  && cp /root/go/bin/mhsendmail /usr/local/bin/mhsendmail

RUN echo "sendmail_path = /usr/bin/mhsendmail --smtp-addr mailhog:1025" >> /usr/local/etc/php/conf.d/sendmail.ini

RUN mkdir -p /var/www/html

RUN chown laravel:laravel /var/www/html

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql
