<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface;

class RouteCreateResult
{
    protected RouteKeyInterface $route;

    public function __construct(RouteKeyInterface $route)
    {
        $this->route = $route;
    }

    public function getRoute(): RouteKeyInterface
    {
        return $this->route;
    }
}
