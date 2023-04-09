DOCKER_COMPOSE = docker compose

.PHONY: build-dev build-prod init dev stop test
build-dev:
	$(DOCKER_COMPOSE) -f docker-compose.dev.yml build

build-prod:
	$(DOCKER_COMPOSE) -f docker-compose.prod.yml build

init:
	$(DOCKER_COMPOSE) -f docker-compose.dev.yml run --rm app composer install --no-interaction --no-progress --optimize-autoloader

dev:
	$(DOCKER_COMPOSE) -f docker-compose.dev.yml up -d

stop:
	$(DOCKER_COMPOSE) -f docker-compose.dev.yml down

test:
	$(DOCKER_COMPOSE) -f docker-compose.prod.yml run --rm app vendor/bin/phpunit
