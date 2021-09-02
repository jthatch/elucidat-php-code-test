help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

# basic vars
image-name :=elucidat-php-code-test
php-image  :=php:8-cli
uid        :=$(shell id -u)
gid        :=$(shell id -g)

# define our reusable docker run commands
define DOCKER_RUN_PHP
docker run -it --rm \
	--name "$(image-name)" \
	-u "$(uid):$(gid)" \
	-v "$(PWD):/usr/src/elucidat" \
	-w /usr/src/elucidat \
	"$(php-image)"
endef

define DOCKER_RUN_PHP_XDEBUG
docker run -it --rm \
	--name "$(image-name)-xdebug" \
	--network=host\
	-u "$(uid):$(gid)" \
	-e PHP_IDE_CONFIG="serverName=$(image-name)" \
	-v "$(PWD):/usr/src/elucidat" \
	-v "$(PWD)/xdebug.ini:/usr/local/etc/php/conf.d/xdebug.ini" \
	-w /usr/src/elucidat \
	mileschou/xdebug:8.0
endef

define DOCKER_RUN_COMPOSER
docker run --rm -it --tty \
    	-u "$(uid):$(gid)" \
    	-v "$(PWD):/app" \
    	composer
endef

tests: ## runs the kahlan tests within the docker container
ifneq ("$(wildcard vendor)", "")
	$(DOCKER_RUN_PHP) php vendor/bin/kahlan
else
	@echo "\nFirst run detected! No vendor/ folder found, running composer update...\n"
	make composer
	make tests
endif

composer: ## Runs `composer update` on CWD, specify other commands via cmd=
ifdef cmd
	$(DOCKER_RUN_COMPOSER) $(cmd)
else
	$(DOCKER_RUN_COMPOSER) update
endif

shell: ## Launch a shell into the docker container
	$(DOCKER_RUN_PHP) /bin/bash

xdebug: ## Launch a php container with xdebug (port 9000)
	$(DOCKER_RUN_PHP_XDEBUG) /bin/bash

xdebug-down: ## Launch a php container with xdebug (port 9000)
	docker stop $(image-name)-xdebug

down: ## stop's the php docker container
	docker stop $(image-name)

cleanup: ## remove all docker images
	docker rm $$(docker ps -a | grep '$(image-name)' | awk '{print $$1}') --force
