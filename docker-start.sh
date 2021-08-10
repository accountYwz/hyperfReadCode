#!/bin/bash

cd "$( dirname "$0"  )"

CONTAINER_NAME="juling-msb"

PORT="23333"

docker run -it -d -p ${PORT}:${PORT} --name ${CONTAINER_NAME} juling/microservice-base:1.0

