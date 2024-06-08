<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/../dev-ops/bin/phpstan/src',
        __DIR__ . '/../src',
        __DIR__ . '/../test',
        __DIR__ . '/../test-composer-integration/package-package/src',
        __DIR__ . '/../test-composer-integration/portal-package/src',
        __DIR__ . '/../test-composer-integration/portal-package-extension/src',
        __DIR__ . '/../test-suite-portal-test-portal/src',
        __DIR__ . '/../test-suite-portal-test-portal/test',
    ])
    ->withPhpSets(php83: true)
    ->withSets([
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
        PHPUnitSetList::PHPUNIT_100,
    ])
    ->withRules([
        AddTypeToConstRector::class,
        AddVoidReturnTypeWhereNoReturnRector::class,
        AddOverrideAttributeToOverriddenMethodsRector::class,
    ]);
