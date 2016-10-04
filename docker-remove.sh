#!/bin/bash

./docker-stop.sh

# Remove all images
docker rm $(docker ps -a -q)

# Remove all networks
docker network rm $(docker network ls -q)
