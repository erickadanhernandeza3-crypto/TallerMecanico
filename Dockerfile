FROM php:8.0-apache

# Debian 11 ya no tiene soporte: sus paquetes de seguridad solo quedan en el archivo historico
RUN find /etc/apt -type f \( -name '*.list' -o -name '*.sources' \) -exec sed -i 's|deb.debian.org|archive.debian.org|g' {} + \
 && apt-get -o Acquire::Check-Valid-Until=false update \
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
