#!/bin/sh

# NOTE: have to be part of the `docker` group (relog on adding yourself to the group)

# NOTE: you have to rebuild the docker image on changing this dockerfile
if ! docker image ls webtech | grep -q webtech; then
    docker build -t webtech .
fi

if [ $? -ne 0 ]; then
    exit 1
fi

docker run \
    -p 80:80 \
    -p 3306:3306 \
    -v ${PWD}/html:/app \
    --name webtech \
    webtech
