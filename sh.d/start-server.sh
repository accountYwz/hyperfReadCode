#!/bin/bash

cd /opt/from-host

composer dump-autoload -o

php watch
