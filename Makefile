SHELL := /bin/bash
PHP := $(shell which php) $(PHP_EXTRA_ARGS)
COMPOSER := $(PHP) $(shell which composer) $(COMPOSER_EXTRA_ARGS)
PHPUNIT_EXTRA_ARGS := --config=test/phpunit.xml
PHPUNIT := $(PHP) vendor/bin/phpunit $(PHPUNIT_EXTRA_ARGS)
CURL := $(shell which curl)
JQ := $(shell which jq)
XSLTPROC := $(shell which xsltproc)
JSON_FILES := $(shell find . -name '*.json' -not -path './vendor/*' -not -path './.build/*' -not -path './dev-ops/bin/*/vendor/*' -not -path './src/Core/vendor/*' -not -path './src/DatasetBase/vendor/*' -not -path './src/PortalBase/vendor/*' -not -path './src/StorageBase/vendor/*' -not -path './src/TestSuiteStorage/vendor/*' -not -path './src/UiAdminBase/vendor/*' -not -path './test/Core/Fixture/_files/portal-node-configuration-invalid.json')
GIT := $(shell which git)
PHPSTAN_FILE := dev-ops/bin/phpstan/vendor/bin/phpstan
COMPOSER_NORMALIZE_PHAR := https://github.com/ergebnis/composer-normalize/releases/download/2.22.0/composer-normalize.phar
COMPOSER_NORMALIZE_FILE := dev-ops/bin/composer-normalize
COMPOSER_REQUIRE_CHECKER_PHAR := https://github.com/maglnet/ComposerRequireChecker/releases/download/3.8.0/composer-require-checker.phar
COMPOSER_REQUIRE_CHECKER_FILE := dev-ops/bin/composer-require-checker
PHPMD_PHAR := https://github.com/phpmd/phpmd/releases/download/2.11.1/phpmd.phar
PHPMD_FILE := dev-ops/bin/phpmd
PHPCPD_PHAR := https://phar.phpunit.de/phpcpd.phar
PHPCPD_FILE := dev-ops/bin/phpcpd
PSALM_FILE := dev-ops/bin/psalm/vendor/bin/psalm
COMPOSER_UNUSED_FILE := dev-ops/bin/composer-unused/vendor/bin/composer-unused
EASY_CODING_STANDARD_FILE := dev-ops/bin/easy-coding-standard/vendor/bin/ecs
PHPCHURN_FILE := dev-ops/bin/php-churn/vendor/bin/churn

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
	[[ ! -f dev-ops/bin/composer-normalize ]] || rm -f dev-ops/bin/composer-normalize
	[[ ! -f dev-ops/bin/composer-require-checker ]] || rm -f dev-ops/bin/composer-require-checker
	[[ ! -d dev-ops/bin/composer-unused/vendor ]] || rm -rf dev-ops/bin/composer-unused/vendor
	[[ ! -d dev-ops/bin/easy-coding-standard/vendor ]] || rm -rf dev-ops/bin/easy-coding-standard/vendor
	[[ ! -f dev-ops/bin/phpmd ]] || rm -f dev-ops/bin/phpmd
	[[ ! -f dev-ops/bin/phpcpd ]] || rm -f dev-ops/bin/phpcpd
	[[ ! -d dev-ops/bin/phpstan/vendor ]] || rm -rf dev-ops/bin/phpstan/vendor
	[[ ! -d dev-ops/bin/psalm/vendor ]] || rm -rf dev-ops/bin/psalm/vendor
	[[ ! -d dev-ops/bin/php-churn/vendor ]] || rm -rf dev-ops/bin/php-churn/vendor

.PHONY: it
it: cs-fix cs test ## Fix code style and run unit tests

.PHONY: coverage
coverage: vendor .build test-setup-fixture run-phpunit-coverage test-clean-fixture ## Run phpunit coverage tests

.PHONY: run-phpunit-coverage
run-phpunit-coverage:
	$(PHPUNIT) --coverage-text

.PHONY: cs
cs: cs-php cs-phpstan cs-psalm cs-phpmd cs-soft-require cs-composer-unused cs-composer-normalize cs-json cs-phpchurn ## Run every code style check target

.PHONY: cs-php
cs-php: vendor .build $(EASY_CODING_STANDARD_FILE) ## Run easy-coding-standard for code style analysis
	$(PHP) $(EASY_CODING_STANDARD_FILE) check --config=dev-ops/ecs.php

.PHONY: cs-phpstan
cs-phpstan: vendor .build $(PHPSTAN_FILE) ## Run phpstan for static code analysis
	[[ -z "${CI}" ]] || $(PHP) $(PHPSTAN_FILE) analyse --level 8 -c dev-ops/phpstan.neon --error-format=junit > .build/phpstan.junit.xml
	[[ -n "${CI}" ]] || $(PHP) $(PHPSTAN_FILE) analyse --level 8 -c dev-ops/phpstan.neon

.PHONY: cs-psalm
cs-psalm: vendor .build $(PSALM_FILE) ## Run psalm for static code analysis
	$(PHP) $(PSALM_FILE) -c $(shell pwd)/dev-ops/psalm.xml

.PHONY: cs-phpmd
cs-phpmd: vendor .build $(PHPMD_FILE) ## Run php mess detector for static code analysis
	[[ -z "${CI}" ]] || [[ -f .build/phpmd-junit.xslt ]] || $(CURL) https://phpmd.org/junit.xslt -o .build/phpmd-junit.xslt
	[[ -z "${CI}" ]] || $(PHP) $(PHPMD_FILE) src/Core xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-core.junit.xml
	[[ -z "${CI}" ]] || $(PHP) $(PHPMD_FILE) src/DatasetBase xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-dataset-base.junit.xml
	[[ -z "${CI}" ]] || $(PHP) $(PHPMD_FILE) src/PortalBase xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-portal-base.junit.xml
	[[ -z "${CI}" ]] || $(PHP) $(PHPMD_FILE) src/StorageBase xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-storage-base.junit.xml
	[[ -z "${CI}" ]] || $(PHP) $(PHPMD_FILE) src/TestSuiteStorage xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-test-suite-storage.junit.xml
	[[ -z "${CI}" ]] || $(PHP) $(PHPMD_FILE) src/UiAdminBase xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-ui-admin-base.junit.xml
	$(PHP) $(PHPMD_FILE) src ansi dev-ops/phpmd.xml

.PHONY: cs-phpcpd
cs-phpcpd: vendor .build $(PHPCPD_FILE) ## Run php copy paste detector for static code analysis
	$(PHP) $(PHPCPD_FILE) --fuzzy src/Core
	$(PHP) $(PHPCPD_FILE) --fuzzy src/DatasetBase
	$(PHP) $(PHPCPD_FILE) --fuzzy src/PortalBase
	$(PHP) $(PHPCPD_FILE) --fuzzy src/StorageBase
	$(PHP) $(PHPCPD_FILE) --fuzzy src/TestSuiteStorage
	$(PHP) $(PHPCPD_FILE) --fuzzy src/UiAdminBase

.PHONY: cs-composer-unused
cs-composer-unused: vendor $(COMPOSER_UNUSED_FILE) ## Run composer-unused to detect once-required packages that are not used anymore
	$(PHP) $(COMPOSER_UNUSED_FILE) --no-progress
	cd src/Core && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress
	cd src/DatasetBase && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress
	cd src/PortalBase && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress
	cd src/StorageBase && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress
	cd src/TestSuiteStorage && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress
	cd src/UiAdminBase && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress

.PHONY: cs-soft-require
cs-soft-require: vendor .build $(COMPOSER_REQUIRE_CHECKER_FILE) ## Run composer-require-checker to detect library usage without requirement entry in composer.json
	$(PHP) $(COMPOSER_REQUIRE_CHECKER_FILE) check --config-file=$(shell pwd)/dev-ops/composer-soft-requirements.json composer.json

.PHONY: cs-composer-normalize
cs-composer-normalize: vendor $(COMPOSER_NORMALIZE_FILE) ## Run composer-normalize for composer.json style analysis
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/Core/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/DatasetBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/PortalBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/StorageBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/TestSuiteStorage/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/UiAdminBase/composer.json

.PHONY: cs-json
cs-json: $(JSON_FILES) ## Run jq on every json file to ensure they are parsable and therefore valid

.PHONY: cs-phpchurn
cs-phpchurn: vendor .build $(PHPCHURN_FILE) ## Run php-churn for prediction of refactoring cases
	$(PHP) $(PHPCHURN_FILE) run --configuration dev-ops/churn.yml --format text

.PHONY: $(JSON_FILES)
$(JSON_FILES):
	$(JQ) . "$@"

.PHONY: cs-fix ## Run all code style fixer that change files
cs-fix: cs-fix-composer-normalize cs-fix-php

.PHONY: cs-fix-composer-normalize
cs-fix-composer-normalize: vendor $(COMPOSER_NORMALIZE_FILE) ## Run composer-normalize for automatic composer.json style fixes
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/Core/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/DatasetBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/PortalBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/StorageBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/TestSuiteStorage/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/UiAdminBase/composer.json

.PHONY: cs-fix-php
cs-fix-php: vendor .build $(EASY_CODING_STANDARD_FILE) ## Run easy-coding-standard for automatic code style fixes
	$(PHP) $(EASY_CODING_STANDARD_FILE) check --config=dev-ops/ecs.php --fix

.PHONY: infection
infection: vendor .build ## Run infection tests
	# Can be simplified when infection/infection#1283 is resolved
	[[ -d .build/phpunit-logs ]] || mkdir -p .build/.phpunit-coverage
	$(PHPUNIT) --coverage-xml=.build/.phpunit-coverage/index.xml --log-junit=.build/.phpunit-coverage/infection.junit.xml
	$(PHP) vendor/bin/infection --min-covered-msi=80 --min-msi=80 --configuration=dev-ops/infection.json --coverage=../.build/.phpunit-coverage --show-mutations --no-interaction

.PHONY: test
test: test-setup-fixture run-phpunit test-clean-fixture ## Run phpunit for unit tests

.PHONY: run-phpunit
run-phpunit: vendor .build
	$(PHPUNIT) --log-junit=.build/.phpunit-coverage/phpunit.junit.xml

test/%Test.php: vendor
	$(PHPUNIT) "$@"

$(PHPSTAN_FILE): ## Install phpstan executable
	$(COMPOSER) install -d dev-ops/bin/phpstan

$(COMPOSER_NORMALIZE_FILE): ## Install composer-normalize executable
	$(CURL) -L $(COMPOSER_NORMALIZE_PHAR) -o $(COMPOSER_NORMALIZE_FILE)

$(COMPOSER_REQUIRE_CHECKER_FILE): ## Install composer-require-checker executable
	$(CURL) -L $(COMPOSER_REQUIRE_CHECKER_PHAR) -o $(COMPOSER_REQUIRE_CHECKER_FILE)

$(PHPMD_FILE): ## Install phpmd executable
	$(CURL) -L $(PHPMD_PHAR) -o $(PHPMD_FILE)

$(PHPCPD_FILE): ## Install phpcpd executable
	$(CURL) -L $(PHPCPD_PHAR) -o $(PHPCPD_FILE)

$(PSALM_FILE): ## Install psalm executable
	$(COMPOSER) install -d dev-ops/bin/psalm

$(COMPOSER_UNUSED_FILE): ## Install composer-unused executable
	$(COMPOSER) install -d dev-ops/bin/composer-unused

$(EASY_CODING_STANDARD_FILE): ## Install easy-coding-standard executable
	$(COMPOSER) install -d dev-ops/bin/easy-coding-standard

$(PHPCHURN_FILE): ## Install php-churn executable
	$(COMPOSER) install -d dev-ops/bin/php-churn

.PHONY: composer-update
composer-update:
	[[ -f vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update

vendor: composer-update

.PHONY: .build
.build:
	[[ -d .build ]] || mkdir .build

composer.lock: vendor

.PHONY: test-refresh-fixture
test-refresh-fixture: test-setup-fixture test-clean-fixture

.PHONY: test-setup-fixture
test-setup-fixture: vendor
	[[ ! -d test-composer-integration/vendor ]] || rm -rf test-composer-integration/vendor
	[[ ! -f test-composer-integration/composer.lock ]] || rm test-composer-integration/composer.lock
	composer install -d test-composer-integration/

.PHONY: test-clean-fixture
test-clean-fixture:
	[[ ! -d test-composer-integration/vendor ]] || rm -rf test-composer-integration/vendor

.PHONY: build-packages
build-packages:
	dev-ops/bin/build-subpackage Core
	dev-ops/bin/build-subpackage DatasetBase
	dev-ops/bin/build-subpackage PortalBase
	dev-ops/bin/build-subpackage StorageBase
	dev-ops/bin/build-subpackage TestSuiteStorage
	dev-ops/bin/build-subpackage UiAdminBase

.PHONY: publish-packages
publish-packages: build-packages
	$(GIT) add -A
	$(GIT) commit -m "Build subpackages"
	dev-ops/bin/publish-subpackage Core core "$(TAG)" "$(BRANCH)"
	dev-ops/bin/publish-subpackage DatasetBase dataset-base "$(TAG)" "$(BRANCH)"
	dev-ops/bin/publish-subpackage PortalBase portal-base "$(TAG)" "$(BRANCH)"
	dev-ops/bin/publish-subpackage StorageBase storage-base "$(TAG)" "$(BRANCH)"
	dev-ops/bin/publish-subpackage TestSuiteStorage test-suite-storage "$(TAG)" "$(BRANCH)"
	dev-ops/bin/publish-subpackage UiAdminBase ui-admin-base "$(TAG)" "$(BRANCH)"
	$(GIT) reset --hard HEAD^1

.PHONY: subtree-merge
subtree-merge: ## Merge core and base packages into framework
	git subtree add -P src/DatasetBase ../heptaconnect-dataset-base master
	git subtree add -P src/PortalBase ../heptaconnect-portal-base master
	git subtree add -P src/StorageBase ../heptaconnect-storage-base master
	git subtree add -P src/Core ../heptaconnect-core master
