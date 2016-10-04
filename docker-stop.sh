#!/bin/bash

docker stop $(docker ps -a -q)
docker-compose stop
