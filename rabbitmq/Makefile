#!/bin/bash

DOCKER_BE = symfony-api-platform-rabbitmq
OS := $(shell uname)

ifeq ($(OS), Darwin)
  UID = $(shell id -u)
else ifeq ($(OS), Linux)
  UID = $(shell id -u)
else
  UID = 1000
endif

help: ## Show this help message
  @echo 'usage: make [target]'
  @echo
  @echo 'targets'
  @echo '^(.+)+\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

run: ## Start the containers
  docker network create symfony-network || true
  U_ID=${UID} docker-compose up -d

stop: ## Stop the containers
  U_ID=${UID} docker-compose stop

restart: ## Restart the containers
  $(MAKE) stop && $(MAKE) run