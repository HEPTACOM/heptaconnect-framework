SHELL := /bin/bash
PHP := $(shell which php) $(PHP_EXTRA_ARGS)
COMPOSER := $(PHP) $(shell which composer) $(COMPOSER_EXTRA_ARGS)
PHPUNIT_EXTRA_ARGS :=
PHPUNIT := $(PHP) vendor/bin/phpunit $(PHPUNIT_EXTRA_ARGS)

.DEFAULT_GOAL := help
.PHONY: help
help: ## List useful make targets
	@echo 'Available make targets'
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: all
all: clean coverage ## Cleans up and runs tests

.PHONY: clean
clean: ## Cleans up all ignored files and directories
	[[ ! -f composer.lock ]] || rm composer.lock
	[[ ! -d vendor ]] || rm -rf vendor

.PHONY: coverage
coverage: vendor ## Runs phpunit coverage
	$(PHPUNIT) --config=test/phpunit.xml --log-junit=../.build/.phpunit-coverage-test-suite-portal-test/phpunit.junit.xml --coverage-text

.PHONY: test
test: vendor ## Runs phpunit tests
	$(PHPUNIT) --config=test/phpunit.xml --log-junit=../.build/.phpunit-coverage-test-suite-portal-test/phpunit.junit.xml

.PHONY: composer-update
composer-update:
	[[ -f vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update

vendor: composer-update

composer.lock: vendor
