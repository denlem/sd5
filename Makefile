# Executables (local)
DOCKER_COMP = docker compose

sh: ## Console
	docker exec -it esports-php-fpm bash

shr: ## Console from root
	docker exec -it -u root esports-php-fpm bash

sh-n: ## Console
	docker exec -it esports_nginx bash

up: ## Console from root
	docker compose up -d

down: ## Console from root
	docker compose down

ccd: ## Console from root
	yes | docker container prune ; yes | docker image prune ; yes | docker system prune --volumes ; yes | docker builder prune

build b: ## Console from root
	docker compose build

buildnc bnc: ## Console from root
	docker compose build --no-cache

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow