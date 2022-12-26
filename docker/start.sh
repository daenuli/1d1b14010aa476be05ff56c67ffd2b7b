#!/usr/bin/env bash
set -e

role=${CONTAINER_ROLE:-app}

if [ "$role" = "app" ]; then
    exec php-fpm
elif [ "$role" = "queue" ]; then
    echo "Running the queue..."
    /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
else
    echo "Could not match the container role \"$role\""
    exit 1
fi