<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;

return static function (Configuration $config): Configuration {
    return $config
    // soft requirement for dependency injection provided for portals
        ->addNamedFilter(NamedFilter::fromString('symfony/yaml'));
};
