#!/bin/sh

# NOTE: have to be part of the `docker` group (relog on adding yourself to the group)

git submodule update --init --recursive
docker build -t webtech .

if [ $? -ne 0 ]; then
    exit 1
fi

docker run \
    -p 80:80 \
    -p 3306:3306 \
    -v ${PWD}/html:/app \
    --network="host" \
    webtech
