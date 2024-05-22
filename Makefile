# Executables (local)
DOCKER_COMP = docker compose

sh: ## Enter to php console
	docker exec -it esports_php_fpm bash

shr: ## Enter to php console from root
	docker exec -it -u root esports_php_fpm bash

sh-n: ## Enter to nginx console
	docker exec -it esports_nginx bash

sh-sw: ## Enter to swagger console
	docker exec -it esports_swagger sh

up: ## Start project
	docker compose up -d

down: ## Down containers
	docker compose down

ccd: ## Clear Docker cache for rebuild
	yes | docker container prune ; yes | docker image prune ; yes | docker system prune --volumes ; yes | docker builder prune

build b: ## Build project with cache
	docker compose build

build-nc bnc: ## Build project with no-cache
	docker compose build --no-cache

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

swagger-doc:
	docker exec esports_php_fpm ./vendor/bin/openapi -o config/docker/swagger/apidoc.json src/Api/