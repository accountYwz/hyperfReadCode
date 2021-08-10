#!/bin/bash

cd "$( dirname "${0}"  )"

if [ "$1" == "" ]; then
    echo "未传入标签，请重新输入，eg: ${0} 1.0.0"
    exit 1
fi
    
TAG=$1

DOCKER_NAME="juling/microservice-base"

MD5=`md5 -q Dockerfile`

IMAGE_FULL_NAME=${DOCKER_NAME}:${MD5}

IMAGE_CONTENT_FULL_NAME=${DOCKER_NAME}:${TAG}

RESULT=`docker images ${IMAGE_FULL_NAME} | grep ${MD5}`

# 基础镜像
if [ "${RESULT}" == "" ]; then
    docker build -f ./Dockerfile -t ${IMAGE_FULL_NAME} .
    echo "基础镜像编译完成"
else
    echo "基础镜像已存在，未进行编译"
fi

# 内容镜像
REPLACE_RESULT=`python3 ./docker-replace_image.py ${IMAGE_FULL_NAME}`

if [ "${REPLACE_RESULT}" ]; then
    echo "出错了"
    echo ${REPLACE_RESULT}
else
    docker build -f ./Dockerfile.content -t ${IMAGE_CONTENT_FULL_NAME} .
fi

echo "执行完成"
