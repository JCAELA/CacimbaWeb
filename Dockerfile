FROM php:8.3-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends libpq-dev \
 && docker-php-ext-install pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*

# Exactly one Apache MPM (prefork, needed by mod_php)
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf \
 && a2enmod mpm_prefork rewrite \
 && echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
 && a2enconf servername

# Startup script created inside the image (no extra file needed in the repo)
RUN printf '%s\n' \
 '#!/bin/sh' \
 'PORT="${PORT:-8080}"' \
 'echo "Starting Apache on port ${PORT}"' \
 'echo "Listen ${PORT}" > /etc/apache2/ports.conf' \
 'sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf' \
 'apache2ctl configtest || true' \
 'exec apache2-foreground' \
 > /usr/local/bin/start.sh \
 && chmod +x /usr/local/bin/start.sh

COPY . /var/www/html/

CMD ["/usr/local/bin/start.sh"]
