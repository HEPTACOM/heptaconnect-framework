SHELL := /bin/bash
PHP := "$(shell which php)" $(PHP_EXTRA_ARGS)
COMPOSER := $(PHP) "$(shell which composer)" $(COMPOSER_EXTRA_ARGS)
PHPUNIT_EXTRA_ARGS := --config=test/phpunit.xml
PHPUNIT := $(PHP) vendor/bin/phpunit $(PHPUNIT_EXTRA_ARGS)
INFECTION := $(PHP) vendor/bin/infection $(INFECTION_EXTRA_ARGS)
CURL := "$(shell which curl)"
JQ := "$(shell which jq)"
XSLTPROC := "$(shell which xsltproc)"
JSON_FILES := $(shell find . -name '*.json' -not -path './vendor/*' -not -path './.build/*' -not -path './dev-ops/bin/*/vendor/*' -not -path './src/Core/vendor/*' -not -path './src/DatasetBase/vendor/*' -not -path './src/PortalBase/vendor/*' -not -path './src/StorageBase/vendor/*' -not -path './src/TestSuitePortal/vendor/*' -not -path './src/TestSuiteStorage/vendor/*' -not -path './src/UiAdminBase/vendor/*' -not -path './src/Utility/vendor/*' -not -path './test/Core/Fixture/_files/portal-node-configuration-invalid.json' -not -path './test-suite-portal-test-portal/vendor/*')
GIT := "$(shell which git)"
PHPSTAN_FILE := dev-ops/bin/phpstan/vendor/bin/phpstan
COMPOSER_NORMALIZE_PHAR := https://github.com/ergebnis/composer-normalize/releases/download/2.22.0/composer-normalize.phar
COMPOSER_NORMALIZE_FILE := dev-ops/bin/composer-normalize
COMPOSER_REQUIRE_CHECKER_PHAR := https://github.com/maglnet/ComposerRequireChecker/releases/download/4.11.0/composer-require-checker.phar
COMPOSER_REQUIRE_CHECKER_FILE := dev-ops/bin/composer-require-checker
PHPMD_PHAR := https://github.com/phpmd/phpmd/releases/download/2.11.1/phpmd.phar
PHPMD_FILE := dev-ops/bin/phpmd
PHPCPD_PHAR := https://phar.phpunit.de/phpcpd.phar
PHPCPD_FILE := dev-ops/bin/phpcpd
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
clean: clean-package-vendor ## Cleans up all ignored files and directories
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
	[[ ! -d test/Core/Fixture/_files/portal_filesystem ]] || rm -rf test/Core/Fixture/_files/portal_filesystem
	make -C test-suite-portal-test-portal clean

.PHONY: clean-package-vendor
clean-package-vendor:
	[[ ! -d src/Core/vendor ]] || rm -rf src/Core/vendor
	[[ ! -f src/Core/composer.lock ]] || rm -f src/Core/composer.lock
	[[ ! -d src/DatasetBase/vendor ]] || rm -rf src/DatasetBase/vendor
	[[ ! -f src/DatasetBase/composer.lock ]] || rm -f src/DatasetBase/composer.lock
	[[ ! -d src/PortalBase/vendor ]] || rm -rf src/PortalBase/vendor
	[[ ! -f src/PortalBase/composer.lock ]] || rm -f src/PortalBase/composer.lock
	[[ ! -d src/StorageBase/vendor ]] || rm -rf src/StorageBase/vendor
	[[ ! -f src/StorageBase/composer.lock ]] || rm -f src/StorageBase/composer.lock
    # TODO add portal test suite
	[[ ! -d src/TestSuiteStorage/vendor ]] || rm -rf src/TestSuiteStorage/vendor
	[[ ! -f src/TestSuiteStorage/composer.lock ]] || rm -f src/TestSuiteStorage/composer.lock
	[[ ! -d src/UiAdminBase/vendor ]] || rm -rf src/UiAdminBase/vendor
	[[ ! -f src/UiAdminBase/composer.lock ]] || rm -f src/UiAdminBase/composer.lock
	[[ ! -d src/Utility/vendor ]] || rm -rf src/Utility/vendor
	[[ ! -f src/Utility/composer.lock ]] || rm -f src/Utility/composer.lock

.PHONY: it
it: cs-fix cs coverage ## Fix code style and run unit tests

.PHONY: coverage
coverage: vendor .build test-setup-fixture clean-package-vendor run-phpunit-coverage test-clean-fixture ## Run phpunit coverage tests

.PHONY: run-phpunit-coverage
run-phpunit-coverage:
	$(PHPUNIT) --coverage-text
	make -C test-suite-portal-test-portal coverage

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
cs-phpmd: vendor .build $(PHPMD_FILE) ## Run php mess detector for static code analysis
	[[ -z "${CI}" ]] || [[ -f .build/phpmd-junit.xslt ]] || $(CURL) https://phpmd.org/junit.xslt -o .build/phpmd-junit.xslt
	[[ -z "${CI}" ]] || $(PHP) -d 'error_reporting=E_ALL & ~E_DEPRECATED' $(PHPMD_FILE) src/Core xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-core.junit.xml && exit $${PIPESTATUS[0]}
	[[ -z "${CI}" ]] || $(PHP) -d 'error_reporting=E_ALL & ~E_DEPRECATED' $(PHPMD_FILE) src/DatasetBase xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-dataset-base.junit.xml && exit $${PIPESTATUS[0]}
	[[ -z "${CI}" ]] || $(PHP) -d 'error_reporting=E_ALL & ~E_DEPRECATED' $(PHPMD_FILE) src/PortalBase xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-portal-base.junit.xml && exit $${PIPESTATUS[0]}
	[[ -z "${CI}" ]] || $(PHP) -d 'error_reporting=E_ALL & ~E_DEPRECATED' $(PHPMD_FILE) src/StorageBase xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-storage-base.junit.xml && exit $${PIPESTATUS[0]}
	[[ -z "${CI}" ]] || $(PHP) -d 'error_reporting=E_ALL & ~E_DEPRECATED' $(PHPMD_FILE) src/TestSuitePortal xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-test-suite-portal.junit.xml && exit $${PIPESTATUS[0]}
	[[ -z "${CI}" ]] || $(PHP) -d 'error_reporting=E_ALL & ~E_DEPRECATED' $(PHPMD_FILE) src/TestSuiteStorage xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-test-suite-storage.junit.xml && exit $${PIPESTATUS[0]}
	[[ -z "${CI}" ]] || $(PHP) -d 'error_reporting=E_ALL & ~E_DEPRECATED' $(PHPMD_FILE) src/UiAdminBase xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-ui-admin-base.junit.xml && exit $${PIPESTATUS[0]}
	[[ -z "${CI}" ]] || $(PHP) -d 'error_reporting=E_ALL & ~E_DEPRECATED' $(PHPMD_FILE) src/Utility xml dev-ops/phpmd.xml | $(XSLTPROC) .build/phpmd-junit.xslt - > .build/php-md-utility.junit.xml && exit $${PIPESTATUS[0]}
	$(PHP) $(PHPMD_FILE) src ansi dev-ops/phpmd.xml

.PHONY: cs-phpcpd
cs-phpcpd: vendor clean-package-vendor .build $(PHPCPD_FILE) ## Run php copy paste detector for static code analysis
	# clean up because phpcpd --exclude is not working atm https://github.com/sebastianbergmann/phpcpd/issues/202
	[[ -z "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/Core --log-pmd .build/phpcpd-core.xml
	[[ -n "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/Core
	[[ -z "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/DatasetBase --log-pmd .build/phpcpd-dataset.xml
	[[ -n "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/DatasetBase
	[[ -z "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/PortalBase --log-pmd .build/phpcpd-portal.xml
	[[ -n "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/PortalBase
	[[ -z "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/StorageBase --log-pmd .build/phpcpd-storage.xml
	[[ -n "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/StorageBase
	[[ -z "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/TestSuiteStorage --log-pmd .build/phpcpd-test-suite-storage.xml
	[[ -n "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/TestSuiteStorage
	[[ -z "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/UiAdminBase --log-pmd .build/phpcpd-ui-admin-base.xml
	[[ -n "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/UiAdminBase
	[[ -z "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/Utility --log-pmd .build/phpcpd-utility.xml
	[[ -n "${CI}" ]] || $(PHP) $(PHPCPD_FILE) --fuzzy src/Utility

.PHONY: cs-composer-unused
cs-composer-unused: vendor src/Core/vendor src/DatasetBase/vendor src/PortalBase/vendor src/StorageBase/vendor src/TestSuiteStorage/vendor src/UiAdminBase/vendor src/Utility/vendor $(COMPOSER_UNUSED_FILE) ## Run composer-unused to detect once-required packages that are not used anymore
	$(PHP) $(COMPOSER_UNUSED_FILE) --configuration=dev-ops/composer-unused.php --no-progress
	cd src/Core && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --configuration=../../dev-ops/composer-unused.php --no-progress
	cd src/DatasetBase && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress
	cd src/PortalBase && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --configuration=../../dev-ops/composer-unused-portal-base.php --no-progress
	cd src/StorageBase && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress
# TODO add portal test suite
	cd src/TestSuiteStorage && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress
	cd src/UiAdminBase && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress
	cd src/Utility && $(PHP) ../../$(COMPOSER_UNUSED_FILE) --no-progress

.PHONY: cs-soft-require
cs-soft-require: vendor .build $(COMPOSER_REQUIRE_CHECKER_FILE) ## Run composer-require-checker to detect library usage without requirement entry in composer.json
	$(PHP) $(COMPOSER_REQUIRE_CHECKER_FILE) check --config-file=$(shell pwd)/dev-ops/composer-soft-requirements.json composer.json

.PHONY: cs-composer-normalize
cs-composer-normalize: $(COMPOSER_NORMALIZE_FILE) ## Run composer-normalize for composer.json style analysis
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/Core/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/DatasetBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/PortalBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/StorageBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/TestSuitePortal/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/TestSuiteStorage/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/UiAdminBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff --dry-run --no-check-lock --no-update-lock src/Utility/composer.json

.PHONY: cs-json
cs-json: $(JSON_FILES) ## Run jq on every json file to ensure they are parsable and therefore valid

.PHONY: cs-phpchurn
cs-phpchurn: .build $(PHPCHURN_FILE) ## Run php-churn for prediction of refactoring cases
	$(PHP) $(PHPCHURN_FILE) run --configuration dev-ops/churn.yml --format text

.PHONY: $(JSON_FILES)
$(JSON_FILES):
	$(JQ) . "$@"

.PHONY: cs-fix ## Run all code style fixer that change files
cs-fix: cs-fix-composer-normalize cs-fix-php

.PHONY: cs-fix-composer-normalize
cs-fix-composer-normalize: $(COMPOSER_NORMALIZE_FILE) ## Run composer-normalize for automatic composer.json style fixes
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/Core/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/DatasetBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/PortalBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/StorageBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/TestSuitePortal/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/TestSuiteStorage/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/UiAdminBase/composer.json
	$(PHP) $(COMPOSER_NORMALIZE_FILE) --diff src/Utility/composer.json

.PHONY: cs-fix-php
cs-fix-php: .build $(EASY_CODING_STANDARD_FILE) ## Run easy-coding-standard for automatic code style fixes
	$(PHP) $(EASY_CODING_STANDARD_FILE) check --config=dev-ops/ecs.php --fix

.PHONY: infection
infection: clean test-setup-fixture vendor .build ## Run infection tests
	# Can be simplified when infection/infection#1283 is resolved
	[[ -d .build/phpunit-logs ]] || mkdir -p .build/.phpunit-coverage
	$(PHPUNIT) --coverage-xml=.build/.phpunit-coverage/index.xml --log-junit=.build/.phpunit-coverage/infection.junit.xml
	$(INFECTION) --only-covered --only-covering-test-cases --threads=max --configuration=dev-ops/infection.json --coverage=../.build/.phpunit-coverage --show-mutations --no-interaction

.PHONY: run-phpunit
run-phpunit: vendor .build
	$(PHPUNIT) --log-junit=.build/.phpunit-coverage/phpunit.junit.xml
	make -C test-suite-portal-test-portal test

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

$(COMPOSER_UNUSED_FILE): ## Install composer-unused executable
	$(COMPOSER) install -d dev-ops/bin/composer-unused

$(EASY_CODING_STANDARD_FILE): ## Install easy-coding-standard executable
	$(COMPOSER) install -d dev-ops/bin/easy-coding-standard

$(PHPCHURN_FILE): ## Install php-churn executable
	$(COMPOSER) install -d dev-ops/bin/php-churn

.PHONY: composer-update
composer-update:
	[[ -f vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update

src/Core/vendor:
	[[ -f src/Core/vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update -d src/Core

src/DatasetBase/vendor:
	[[ -f src/DatasetBase/vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update -d src/DatasetBase

src/PortalBase/vendor:
	[[ -f src/PortalBase/vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update -d src/PortalBase

src/StorageBase/vendor:
	[[ -f src/StorageBase/vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update -d src/StorageBase
# TODO add portal test suite

src/TestSuiteStorage/vendor:
	[[ -f src/TestSuiteStorage/vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update -d src/TestSuiteStorage

src/UiAdminBase/vendor:
	[[ -f src/UiAdminBase/vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update -d src/UiAdminBase

src/Utility/vendor:
	[[ -f src/Utility/vendor/autoload.php && -n "${CI}" ]] || $(COMPOSER) update -d src/Utility

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
	make -C test-suite-portal-test-portal clean
	make -C test-suite-portal-test-portal vendor

.PHONY: test-clean-fixture
test-clean-fixture:
	[[ ! -d test-composer-integration/vendor ]] || rm -rf test-composer-integration/vendor
	make -C test-suite-portal-test-portal clean

.PHONY: build-packages
build-packages:
	dev-ops/bin/build-subpackage Core
	dev-ops/bin/build-subpackage DatasetBase
	dev-ops/bin/build-subpackage PortalBase
	dev-ops/bin/build-subpackage StorageBase
	dev-ops/bin/build-subpackage TestSuiteStorage
	dev-ops/bin/build-subpackage UiAdminBase
	dev-ops/bin/build-subpackage Utility
	# TODO Add TestSuitePortal

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
	dev-ops/bin/publish-subpackage Utility utility "$(TAG)" "$(BRANCH)"
	# TODO Add TestSuitePortal
	$(GIT) reset --hard HEAD^1

.PHONY: subtree-merge
subtree-merge: ## Merge core and base packages into framework
	git subtree add -P src/DatasetBase ../heptaconnect-dataset-base master
	git subtree add -P src/PortalBase ../heptaconnect-portal-base master
	git subtree add -P src/StorageBase ../heptaconnect-storage-base master
	git subtree add -P src/Core ../heptaconnect-core master
