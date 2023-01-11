#!/bin/sh

# NOTE: have to be part of the `docker` group (relog on adding yourself to the group)

# NOTE: you have to rebuild the docker image on changing this dockerfile
if ! docker image ls webtech | grep -q webtech; then
    cat <<EOF | docker build -t webtech -
    FROM mattrayner/lamp:latest-2004-php8

    # ..future `RUN` commands go here :)
EOF
fi

docker run -p 80:80 -p 3306:3306 -v ${PWD}/html:/app webtech
