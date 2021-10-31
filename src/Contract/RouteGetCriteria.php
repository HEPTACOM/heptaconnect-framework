<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\RouteKeyCollection;

class RouteGetCriteria
{
    private RouteKeyCollection $routes;

    public function __construct(RouteKeyCollection $routes)
    {
        $this->routes = $routes;
    }

    public function getRoutes(): RouteKeyCollection
    {
        return $this->routes;
    }

    public function setRoutes(RouteKeyCollection $routes): void
    {
        $this->routes = $routes;
    }
}
