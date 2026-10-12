#!/bin/sh
set -e
PORT="${PORT:-8080}"
echo "Starting Apache on port ${PORT}"

echo "Listen ${PORT}" > /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Show config problems in the Railway logs
apache2ctl configtest || true

exec apache2-foreground
