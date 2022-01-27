<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Get;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\RouteKeyCollection;

final class RouteGetCriteria
{
    private RouteKeyCollection $routeKeys;

    public function __construct(RouteKeyCollection $routeKeys)
    {
        $this->routeKeys = $routeKeys;
    }

    public function getRouteKeys(): RouteKeyCollection
    {
        return $this->routeKeys;
    }

    public function setRouteKeys(RouteKeyCollection $routeKeys): void
    {
        $this->routeKeys = $routeKeys;
    }
}
