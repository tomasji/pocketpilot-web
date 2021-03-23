.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "Usage: make <target>\n"} /^[A-Za-z]+:.*?##/ { printf "  %-15s %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

.PHONY: prep up down

prep:
	@mkdir -p log temp node_modules
	@[ -f app/config/config.local.neon ] || cp app/config/config.local.neon-example app/config/config.local.neon

up: prep ## Start dockers
	@USER_ID=`id -u` GROUP_ID=`id -g` docker-compose up

down: ## Stop dockers
	docker-compose down
