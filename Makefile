SHELL := /bin/bash
PHP := "$(shell which php)" $(PHP_EXTRA_ARGS)
COMPOSER := $(PHP) "$(shell which composer)" $(COMPOSER_EXTRA_ARGS)
PHPUNIT_EXTRA_ARGS := --config=test/phpunit.xml
PHPUNIT := $(PHP) vendor/bin/phpunit $(PHPUNIT_EXTRA_ARGS)
INFECTION := $(PHP) vendor/bin/infection $(INFECTION_EXTRA_ARGS)
CURL := "$(shell which curl)"
JQ := "$(shell which jq)"
XSLTPROC := "$(shell which xsltproc)"
JSON_FILES := $(shell find . -name '*.json' -not -path './vendor/*' -not -path './.build/*' -not -path './dev-ops/bin/*/vendor/*')

PHPSTAN_COMPOSER_DIR := dev-ops/bin/phpstan
PHPSTAN_FILE := $(PHPSTAN_COMPOSER_DIR)/vendor/bin/phpstan

COMPOSER_NORMALIZE_PHAR := https://github.com/ergebnis/composer-normalize/releases/download/2.42.0/composer-normalize.phar
COMPOSER_NORMALIZE_FILE := dev-ops/bin/composer-normalize
COMPOSER_NORMALIZE_EXTRA_ARGS := --indent-size=4 --indent-style=space --no-check-lock --no-update-lock

COMPOSER_REQUIRE_CHECKER_PHAR := https://github.com/maglnet/ComposerRequireChecker/releases/download/4.11.0/composer-require-checker.phar
COMPOSER_REQUIRE_CHECKER_FILE := dev-ops/bin/composer-require-checker

PHPMD_PHAR := https://github.com/phpmd/phpmd/releases/download/2.15.0/phpmd.phar
PHPMD_FILE := dev-ops/bin/phpmd

PHPCPD_PHAR := https://phar.phpunit.de/phpcpd.phar
PHPCPD_FILE := dev-ops/bin/phpcpd

COMPOSER_UNUSED_COMPOSER_DIR := dev-ops/bin/composer-unused
COMPOSER_UNUSED_FILE := $(COMPOSER_UNUSED_COMPOSER_DIR)/vendor/bin/composer-unused

EASY_CODING_STANDARD_COMPOSER_DIR := dev-ops/bin/easy-coding-standard
EASY_CODING_STANDARD_FILE := $(EASY_CODING_STANDARD_COMPOSER_DIR)/vendor/bin/ecs

PHPCHURN_COMPOSER_DIR := dev-ops/bin/php-churn
PHPCHURN_FILE := $(PHPCHURN_COMPOSER_DIR)/vendor/bin/churn

RECTOR_COMPOSER_DIR := dev-ops/bin/rector
RECTOR_FILE := $(RECTOR_COMPOSER_DIR)/vendor/bin/rector

.DEFAULT_GOAL := help
.PHONY: help
help: ## List useful make targets
	@echo 'Available make targets'
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: all
all: clean it coverage infection ## Cleans up and runs typical tests and style analysis

.PHONY: clean
clean: ## Cleans up all ignored files and directories
	[[ ! -f composer.lock ]] || rm composer.lock
	[[ ! -d vendor ]] || rm -rf vendor
	[[ ! -d .build ]] || rm -rf .build
	[[ ! -f "$(COMPOSER_NORMALIZE_FILE)" ]] || rm -f "$(COMPOSER_NORMALIZE_FILE)"
	[[ ! -f "$(COMPOSER_REQUIRE_CHECKER_FILE)" ]] || rm -f "$(COMPOSER_REQUIRE_CHECKER_FILE)"
	[[ ! -d "$(COMPOSER_UNUSED_COMPOSER_DIR)/vendor" ]] || rm -rf "$(COMPOSER_UNUSED_COMPOSER_DIR)/vendor"
	[[ ! -d "$(EASY_CODING_STANDARD_COMPOSER_DIR)/vendor" ]] || rm -rf "$(EASY_CODING_STANDARD_COMPOSER_DIR)/vendor"
	[[ ! -f "$(PHPMD_FILE)" ]] || rm -f "$(PHPMD_FILE)"
	[[ ! -f "$(PHPCPD_FILE)" ]] || rm -f "$(PHPCPD_FILE)"
	[[ ! -d "$(PHPSTAN_COMPOSER_DIR)/vendor" ]] || rm -rf "$(PHPSTAN_COMPOSER_DIR)/vendor"
	[[ ! -d "$(PHPCHURN_COMPOSER_DIR)/vendor" ]] || rm -rf "$(PHPCHURN_COMPOSER_DIR)/vendor"
	[[ ! -d "$(RECTOR_COMPOSER_DIR)/vendor" ]] || rm -rf "$(RECTOR_COMPOSER_DIR)/vendor"

.PHONY: it
it: cs-fix cs coverage ## Fix code style and run unit tests

.PHONY: coverage
coverage: vendor .build ## Run phpunit coverage tests
	$(PHPUNIT) --coverage-text

.PHONY: tests-without-coverage
tests-without-coverage: vendor .build ## Run phpunit tests without coverage. This is needed as pecl does not serve xdebug 3.2 for php 8.3 . See https://bugs.xdebug.org/view.php?id=2252
	$(PHPUNIT)

.PHONY: cs
cs: cs-php cs-phpstan cs-phpmd cs-soft-require cs-composer-unused cs-composer-normalize cs-json cs-phpchurn ## Run every code style check target

.PHONY: cs-php
cs-php: .build $(EASY_CODING_STANDARD_FILE) ## Run easy-coding-standard for code style analysis
	$(PHP) $(EASY_CODING_STANDARD_FILE) check --config=dev-ops/ecs.php

.PHONY: cs-phpstan
cs-phpstan: vendor .build $(PHPSTAN_FILE) ## Run phpstan for static code analysis
	[[ -z "${CI}" ]] || $(PHP) $(PHPSTAN_FILE) analyse --level 8 -c dev-ops/phpstan.neon --error-format=junit > .build/phpstan.junit.xml
	[[ -n "${CI}" ]] || $(PHP) $(PHPSTAN_FILE) analyse --level 8 -c dev-ops/phpstan.neon

.PHONY: cs-phpmd
cs-phpmd: .build $(PHPMD_FILE) ## Run php mess detector for static code analysis
	[[ -z "${CI}" ]] || [[ -f .build/phpmd-junit.xslt ]] || $(CURL) https://phpmd.org/junit.xslt -o .build/phpmd-junit.xslt
	[[ -z "${CI}" ]] || $(PHP) -d 'error_reporting=E_ALL & ~E_DEPRECATED' $(PHPMD_FILE) src xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md.junit.xml && exit $${PIPESTATUS[0]}
	[[ -n "${CI}" ]] || $(PHP) $(PHPMD_FILE) src ansi dev-ops/phpmd.xml

.PHONY: cs-phpcpd
cs-phpcpd: .build $(PHPCPD_FILE) ## Run php copy paste detector for static code analysis
	[[ -z "${CI}" ]] || $(PHP) "$(PHPCPD_FILE)" --fuzzy src --log-pmd .build/phpcpd.xml
	[[ -n "${CI}" ]] || $(PHP) "$(PHPCPD_FILE)" --fuzzy src

.PHONY: cs-composer-unused
cs-composer-unused: vendor $(COMPOSER_UNUSED_FILE) ## Run composer-unused to detect once-required packages that are not used anymore
	$(PHP) "$(COMPOSER_UNUSED_FILE)" --configuration=dev-ops/composer-unused.php --no-progress

.PHONY: cs-soft-require
cs-soft-require: vendor .build $(COMPOSER_REQUIRE_CHECKER_FILE) ## Run composer-require-checker to detect library usage without requirement entry in composer.json
	$(PHP) "$(COMPOSER_REQUIRE_CHECKER_FILE)" check --config-file=$(shell pwd)/dev-ops/composer-soft-requirements.json composer.json

.PHONY: cs-composer-normalize
cs-composer-normalize: $(COMPOSER_NORMALIZE_FILE) ## Run composer-normalize for composer.json style analysis
	$(PHP) "$(COMPOSER_NORMALIZE_FILE)" $(COMPOSER_NORMALIZE_EXTRA_ARGS) --diff --dry-run composer.json

.PHONY: cs-json
cs-json: $(JSON_FILES) ## Run jq on every json file to ensure they are parsable and therefore valid

.PHONY: cs-phpchurn
cs-phpchurn: .build $(PHPCHURN_FILE) ## Run php-churn for prediction of refactoring cases
	$(PHP) "$(PHPCHURN_FILE)" run --configuration dev-ops/churn.yml --format text

.PHONY: cs-fix-rector
cs-fix-rector: $(RECTOR_FILE) ## Run rector to upgrade PHP code to recent features
	$(PHP) "$(RECTOR_FILE)" --config=dev-ops/rector.php

.PHONY: $(JSON_FILES)
$(JSON_FILES):
	$(JQ) . "$@"

.PHONY: cs-fix ## Run all code style fixer that change files
cs-fix: cs-fix-composer-normalize cs-fix-php

.PHONY: cs-fix-composer-normalize
cs-fix-composer-normalize: $(COMPOSER_NORMALIZE_FILE) ## Run composer-normalize for automatic composer.json style fixes
	$(PHP) "$(COMPOSER_NORMALIZE_FILE)" $(COMPOSER_NORMALIZE_EXTRA_ARGS) --diff composer.json

.PHONY: cs-fix-php
cs-fix-php: .build $(EASY_CODING_STANDARD_FILE) ## Run easy-coding-standard for automatic code style fixes
	$(PHP) "$(EASY_CODING_STANDARD_FILE)" check --config=dev-ops/ecs.php --fix

.PHONY: infection
infection: clean vendor .build ## Run infection tests
	# Can be simplified when infection/infection#1283 is resolved
	[[ -d .build/phpunit-logs ]] || mkdir -p .build/.phpunit-coverage
	$(PHPUNIT) --coverage-xml=.build/.phpunit-coverage/index.xml --log-junit=.build/.phpunit-coverage/infection.junit.xml
	$(INFECTION) --only-covered --only-covering-test-cases --threads=max --configuration=dev-ops/infection.json --coverage=../.build/.phpunit-coverage --show-mutations --no-interaction

$(PHPSTAN_FILE): ## Install phpstan executable
	$(COMPOSER) install -d "$(PHPSTAN_COMPOSER_DIR)"

$(COMPOSER_NORMALIZE_FILE): ## Install composer-normalize executable
	$(CURL) -L "$(COMPOSER_NORMALIZE_PHAR)" -o "$(COMPOSER_NORMALIZE_FILE)"

$(COMPOSER_REQUIRE_CHECKER_FILE): ## Install composer-require-checker executable
	$(CURL) -L "$(COMPOSER_REQUIRE_CHECKER_PHAR)" -o "$(COMPOSER_REQUIRE_CHECKER_FILE)"

$(PHPMD_FILE): ## Install phpmd executable
	$(CURL) -L "$(PHPMD_PHAR)" -o "$(PHPMD_FILE)"

$(PHPCPD_FILE): ## Install phpcpd executable
	$(CURL) -L "$(PHPCPD_PHAR)" -o "$(PHPCPD_FILE)"

$(COMPOSER_UNUSED_FILE): ## Install composer-unused executable
	$(COMPOSER) install -d "$(COMPOSER_UNUSED_COMPOSER_DIR)"

$(EASY_CODING_STANDARD_FILE): ## Install easy-coding-standard executable
	$(COMPOSER) install -d "$(EASY_CODING_STANDARD_COMPOSER_DIR)"

$(PHPCHURN_FILE): ## Install php-churn executable
	$(COMPOSER) install -d "$(PHPCHURN_COMPOSER_DIR)"

$(RECTOR_FILE): ## Install rector executable
	$(COMPOSER) install -d "$(RECTOR_COMPOSER_DIR)"

.PHONY: composer-update
composer-update:
	[[ -f vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update

vendor: composer-update

.PHONY: .build
.build:
	[[ -d .build ]] || mkdir .build

composer.lock: vendor
