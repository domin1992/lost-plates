#!/bin/bash

if [ ! -f ".env" ]; then
    cp .env.example .env
    ./vendor/bin/sail artisan key:generate
else
    echo "File .env already exists"
fi

./vendor/bin/sail up -d

docker exec lostplates cron
./vendor/bin/sail composer install
./vendor/bin/sail npm i

./vendor/bin/sail artisan storage:link

./vendor/bin/sail npm run prod

docker exec lostplates chmod -R 777 .

./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan migrate --seed
