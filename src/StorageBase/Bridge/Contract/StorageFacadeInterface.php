<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Contract;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;

interface StorageFacadeInterface
{
    public function getRouteCreateAction(): RouteCreateActionInterface;

    public function getRouteFindAction(): RouteFindActionInterface;

    public function getRouteGetAction(): RouteGetActionInterface;

    public function getReceptionRouteListAction(): ReceptionRouteListActionInterface;
}
