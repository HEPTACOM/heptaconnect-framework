<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Listing;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface;

class ReceptionRouteListResult
{
    protected RouteKeyInterface $routeKey;

    public function __construct(RouteKeyInterface $routeKey)
    {
        $this->routeKey = $routeKey;
    }

    public function getRouteKey(): RouteKeyInterface
    {
        return $this->routeKey;
    }
}
