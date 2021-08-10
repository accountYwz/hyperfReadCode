FROM hyperf/hyperf:7.4-alpine-v3.11-swoole

MAINTAINER karocxing@163.com

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories \
    && sed -i 's/;error_log = php_errors.log/error_log = php_errors.log/g' /etc/php7/php.ini \
    && apk update \
    && apk add tzdata \
    && cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo "Asia/Shanghai" > /etc/timezone \
    && apk del tzdata \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer

CMD /bin/bash

