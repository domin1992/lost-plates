#!/bin/bash

MAIN_CONTAINER_NAME="lostplates.test"
WWW_DATA="www-data"

docker compose up --detach --build

if [ ! -f ".env" ]; then
    cp .env.local .env
    docker exec $MAIN_CONTAINER_NAME php artisan key:generate
else
    echo "File .env already exists"
fi

docker exec -u $WWW_DATA $MAIN_CONTAINER_NAME composer install
docker exec -u $WWW_DATA $MAIN_CONTAINER_NAME npm i

docker exec -u $WWW_DATA $MAIN_CONTAINER_NAME php artisan storage:link

docker exec -u $WWW_DATA $MAIN_CONTAINER_NAME npm run prod

docker exec $MAIN_CONTAINER_NAME chmod -R 777 .

docker exec -u $WWW_DATA $MAIN_CONTAINER_NAME php artisan config:clear
docker exec -u $WWW_DATA $MAIN_CONTAINER_NAME php artisan config:cache
#docker exec -u $WWW_DATA $MAIN_CONTAINER_NAME php artisan ide-helper:generate
sleep 10
docker exec -u $WWW_DATA $MAIN_CONTAINER_NAME php artisan migrate --seed
