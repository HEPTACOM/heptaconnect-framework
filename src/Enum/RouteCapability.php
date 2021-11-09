<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Enum;

abstract class RouteCapability
{
    public const RECEPTION = 'reception';

    public const ALL = [
        self::RECEPTION,
    ];

    public function isKnown(string $rouceCapability): bool
    {
        return \in_array($rouceCapability, self::ALL);
    }
}
