#!/bin/bash

DOCKER_BE = synfony-api-platform-be
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
  docker network create symfony-api-platform-network || true
  U_ID=${UID} docker-compose up -d

stop: ## Stop the containers
  U_ID=${UID} docker-compose stop

restart: ## Restart the containers
  $(MAKE) stop && $(MAKE) run

prepare: ## Runs backend command
  $(MAKE) composer-install

# Backend Commands
composer-install: ## Install compose dependencies
  U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_BE} composer install --no-scripts --no-interaction --optimize-autloader

migrations: ## Run the migrations
  U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bin/console doctrine:migrations:migrate -n

be-logs: ## Tails the Symfony dev log
  U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} tail -f var/log/dev.log

ssh-be: ## ssh's into the container
  U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash

code-style: ## Run php-cs to fix code styling following Symfony rules
  U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} php-cs-fixer fix src --rules=Symfony
