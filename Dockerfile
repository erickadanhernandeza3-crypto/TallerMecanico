FROM php:8.0-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends mariadb-server \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-install mysqli

COPY deploy/php.ini /usr/local/etc/php/conf.d/taller.ini
COPY deploy/apache-limits.conf /etc/apache2/conf-enabled/limits.conf
COPY deploy/start.sh /usr/local/bin/start.sh
COPY taller_mecanico_checo.sql /opt/seed.sql
COPY . /var/www/html/

# Lo que no debe poder descargarse desde la web
RUN cd /var/www/html \
 && rm -rf deploy Dockerfile render.yaml taller_mecanico_checo.sql .git .gitignore .gitattributes .dockerignore \
 && chmod +x /usr/local/bin/start.sh

CMD ["start.sh"]
