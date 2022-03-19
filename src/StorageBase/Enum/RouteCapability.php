<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Enum;

abstract class RouteCapability
{
    public const RECEPTION = 'reception';

    public const ALL = [
        self::RECEPTION,
    ];

    public static function isKnown(string $routeCapability): bool
    {
        return \in_array($routeCapability, self::ALL, true);
    }
}
