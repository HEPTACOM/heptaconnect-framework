<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Listing;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface;

class ReceptionRouteListResult
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
