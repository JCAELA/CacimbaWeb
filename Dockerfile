FROM php:8.3-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends libpq-dev \
 && docker-php-ext-install pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*

# Startup script created inside the image. It fixes the MPM at every start.
RUN printf '%s\n' \
 '#!/bin/sh' \
 'PORT="${PORT:-8080}"' \
 'echo "Starting Apache on port ${PORT}"' \
 'rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf' \
 'ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load' \
 'ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf' \
 'echo "MPM modules enabled:"; ls /etc/apache2/mods-enabled | grep -i mpm' \
 'echo "MPM LoadModule lines:"; grep -rn "LoadModule mpm" /etc/apache2/ | grep -v "mods-available"' \
 'echo "ServerName localhost" > /etc/apache2/conf-enabled/servername.conf' \
 'echo "Listen ${PORT}" > /etc/apache2/ports.conf' \
 'sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf' \
 'apache2ctl configtest' \
 'exec apache2-foreground' \
 > /usr/local/bin/start.sh \
 && chmod +x /usr/local/bin/start.sh

COPY . /var/www/html/

CMD ["/usr/local/bin/start.sh"]
