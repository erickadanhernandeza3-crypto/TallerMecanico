#!/bin/sh
# Arranca MariaDB (solo accesible dentro del contenedor), carga los datos y levanta Apache.
set -e

export DB_HOST=127.0.0.1 DB_PORT=3306 DB_NAME=taller_mecanico_checo DB_USER=taller
export DB_PASS="$(head -c 18 /dev/urandom | base64 | tr -dc 'A-Za-z0-9')"

mkdir -p /run/mysqld
chown -R mysql:mysql /run/mysqld /var/lib/mysql
[ -d /var/lib/mysql/mysql ] || mysql_install_db --user=mysql --datadir=/var/lib/mysql >/dev/null

mysqld --user=mysql --bind-address=127.0.0.1 --port=3306 \
       --innodb-buffer-pool-size=32M --performance-schema=OFF --max-connections=40 &

i=0
until mysqladmin ping --silent 2>/dev/null; do
    i=$((i + 1))
    [ "$i" -lt 60 ] || { echo "MariaDB no arranco" >&2; exit 1; }
    sleep 1
done

mysql --default-character-set=utf8mb4 < /opt/seed.sql
mysql -e "CREATE OR REPLACE USER 'taller'@'127.0.0.1' IDENTIFIED BY '$DB_PASS';
          GRANT ALL ON taller_mecanico_checo.* TO 'taller'@'127.0.0.1';"

# Render indica el puerto en $PORT
PORT="${PORT:-10000}"
sed -i "s/^Listen .*/Listen $PORT/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:$PORT>/" /etc/apache2/sites-enabled/000-default.conf

exec apache2-foreground
