#!/bin/sh

sleep 5

php bin/console doctrine:migrations:migrate --no-interaction

exec apache2-foreground