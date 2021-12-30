SHELL := /bin/bash
PHP := $(shell which php) $(PHP_EXTRA_ARGS)
COMPOSER := $(PHP) $(shell which composer) $(COMPOSER_EXTRA_ARGS)
PHPUNIT_EXTRA_ARGS := --config=test/phpunit.xml
PHPUNIT := $(PHP) vendor/bin/phpunit $(PHPUNIT_EXTRA_ARGS)
CURL := $(shell which curl)
JQ := $(shell which jq)
JSON_FILES := $(shell find . -name '*.json' -not -path './vendor/*' -not -path './src/Core/vendor/*' -not -path './src/DatasetBase/vendor/*' -not -path './src/PortalBase/vendor/*' -not -path './src/StorageBase/vendor/*' -not -path './src/TestSuiteStorage/vendor/*')
GIT := $(shell which git)

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

.PHONY: it
it: cs-fix cs test ## Fix code style and run unit tests

.PHONY: coverage
coverage: vendor .build test-refresh-fixture ## Run phpunit coverage tests
	$(PHPUNIT) --coverage-text

.PHONY: cs
cs: cs-php cs-phpstan cs-psalm cs-phpmd cs-soft-require cs-composer-unused cs-composer-normalize cs-json ## Run every code style check target

.PHONY: cs-php
cs-php: vendor .build ## Run php-cs-fixer for code style analysis
	$(PHP) vendor/bin/php-cs-fixer fix --dry-run --config=dev-ops/php_cs.php --diff --verbose
	$(PHP) vendor/bin/php-cs-fixer fix --dry-run --config=dev-ops/php_cs.php --format junit > .build/php-cs-fixer.junit.xml

.PHONY: cs-phpstan
cs-phpstan: vendor .build ## Run phpstan for static code analysis
	$(PHP) vendor/bin/phpstan analyse -c dev-ops/phpstan.neon --error-format=junit

.PHONY: cs-psalm
cs-psalm: vendor .build ## Run psalm for static code analysis
	# Bug in psalm expects the cache directory to be in the project parent but is the config parent (https://github.com/vimeo/psalm/pull/3421)
	cd dev-ops && $(PHP) ../vendor/bin/psalm -c $(shell pwd)/dev-ops/psalm.xml

.PHONY: cs-phpmd
cs-phpmd: vendor .build ## Run php mess detector for static code analysis
	# TODO Re-add rulesets/unused.xml when phpmd fixes false-positive UnusedPrivateField
	$(PHP) vendor/bin/phpmd --ignore-violations-on-exit src ansi rulesets/codesize.xml,rulesets/naming.xml
	[[ -f .build/phpmd-junit.xslt ]] || $(CURL) https://phpmd.org/junit.xslt -o .build/phpmd-junit.xslt
	$(PHP) vendor/bin/phpmd src xml rulesets/codesize.xml,rulesets/naming.xml | xsltproc .build/phpmd-junit.xslt - > .build/php-md.junit.xml
	$(PHP) vendor/bin/phpmd src/Core xml rulesets/codesize.xml,rulesets/naming.xml | xsltproc .build/phpmd-junit.xslt - > .build/php-md-core.junit.xml
	$(PHP) vendor/bin/phpmd src/DatasetBase xml rulesets/codesize.xml,rulesets/naming.xml | xsltproc .build/phpmd-junit.xslt - > .build/php-md-dataset-base.junit.xml
	$(PHP) vendor/bin/phpmd src/PortalBase xml rulesets/codesize.xml,rulesets/naming.xml | xsltproc .build/phpmd-junit.xslt - > .build/php-md-portal-base.junit.xml
	$(PHP) vendor/bin/phpmd src/StorageBase xml rulesets/codesize.xml,rulesets/naming.xml | xsltproc .build/phpmd-junit.xslt - > .build/php-md-storage-base.junit.xml
	$(PHP) vendor/bin/phpmd src/TestSuiteStorage xml rulesets/codesize.xml,rulesets/naming.xml | xsltproc .build/phpmd-junit.xslt - > .build/php-md-storage-base.junit.xml

.PHONY: cs-composer-unused
cs-composer-unused: vendor ## Run composer-unused to detect once-required packages that are not used anymore
	$(PHP) vendor/bin/composer-unused --no-progress
	cd src/Core && $(PHP) ../../vendor/bin/composer-unused --no-progress
	cd src/DatasetBase && $(PHP) ../../vendor/bin/composer-unused --no-progress
	cd src/PortalBase && $(PHP) ../../vendor/bin/composer-unused --no-progress
	cd src/StorageBase && $(PHP) ../../vendor/bin/composer-unused --no-progress
	cd src/TestSuiteStorage && $(PHP) ../../vendor/bin/composer-unused --no-progress

.PHONY: cs-soft-require
cs-soft-require: vendor .build ## Run composer-require-checker to detect library usage without requirement entry in composer.json
	$(PHP) vendor/bin/composer-require-checker check --config-file=dev-ops/composer-soft-requirements.json composer.json

.PHONY: cs-composer-normalize
cs-composer-normalize: vendor ## Run composer-normalize for composer.json style analysis
	$(COMPOSER) normalize --diff --dry-run --no-check-lock --no-update-lock composer.json
	$(COMPOSER) normalize --diff --dry-run --no-check-lock --no-update-lock src/Core/composer.json
	$(COMPOSER) normalize --diff --dry-run --no-check-lock --no-update-lock src/DatasetBase/composer.json
	$(COMPOSER) normalize --diff --dry-run --no-check-lock --no-update-lock src/PortalBase/composer.json
	$(COMPOSER) normalize --diff --dry-run --no-check-lock --no-update-lock src/StorageBase/composer.json
	$(COMPOSER) normalize --diff --dry-run --no-check-lock --no-update-lock src/TestSuiteStorage/composer.json

.PHONY: cs-json
cs-json: $(JSON_FILES) ## Run jq on every json file to ensure they are parsable and therefore valid

.PHONY: $(JSON_FILES)
$(JSON_FILES):
	$(JQ) . "$@"

.PHONY: cs-fix ## Run all code style fixer that change files
cs-fix: cs-fix-composer-normalize cs-fix-php

.PHONY: cs-fix-composer-normalize
cs-fix-composer-normalize: vendor ## Run composer-normalize for automatic composer.json style fixes
	$(COMPOSER) normalize --diff composer.json
	$(COMPOSER) normalize --diff src/Core/composer.json
	$(COMPOSER) normalize --diff src/DatasetBase/composer.json
	$(COMPOSER) normalize --diff src/PortalBase/composer.json
	$(COMPOSER) normalize --diff src/StorageBase/composer.json
	$(COMPOSER) normalize --diff src/TestSuiteStorage/composer.json

.PHONY: cs-fix-php
cs-fix-php: vendor .build ## Run php-cs-fixer for automatic code style fixes
	$(PHP) vendor/bin/php-cs-fixer fix --config=dev-ops/php_cs.php --diff --verbose

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

.PHONY: publish-packages
publish-packages: build-packages
	$(GIT) add -A
	$(GIT) commit -m "Build subpackages"
	dev-ops/bin/publish-subpackage Core core "$(TAG)" "$(BRANCH)"
	dev-ops/bin/publish-subpackage DatasetBase dataset-base "$(TAG)" "$(BRANCH)"
	dev-ops/bin/publish-subpackage PortalBase portal-base "$(TAG)" "$(BRANCH)"
	dev-ops/bin/publish-subpackage StorageBase storage-base "$(TAG)" "$(BRANCH)"
	dev-ops/bin/publish-subpackage TestSuiteStorage test-suite-storage "$(TAG)" "$(BRANCH)"
	$(GIT) reset --hard HEAD^1

.PHONY: subtree-merge
subtree-merge: ## Merge core and base packages into framework
	git subtree add -P src/DatasetBase ../heptaconnect-dataset-base master
	git subtree add -P src/PortalBase ../heptaconnect-portal-base master
	git subtree add -P src/StorageBase ../heptaconnect-storage-base master
	git subtree add -P src/Core ../heptaconnect-core master
	git subtree add -P src/TestSuiteStorage ../heptaconnect-test-suite-storage master
