PHP_DOCKER=php:7.4-cli
COMPOSER_DOCKER=composer:2

.PHONY: deps test-unit test-integration php-version

deps:
	docker run --rm -v "$(PWD):/app" -w /app $(COMPOSER_DOCKER) install --ignore-platform-reqs

php-version:
	docker run --rm -v "$(PWD):/app" -w /app $(PHP_DOCKER) php -v

test-unit:
	docker run --rm -v "$(PWD):/app" -w /app $(PHP_DOCKER) php vendor/bin/phpunit tests/unit

test-integration:
	docker run --rm -e KONDUTO_SANDBOX_API_KEY="$(KONDUTO_SANDBOX_API_KEY)" -v "$(PWD):/app" -w /app $(PHP_DOCKER) php vendor/bin/phpunit tests/integration

