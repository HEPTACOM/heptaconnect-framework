<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Contract;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;

interface StorageFacadeInterface
{
    public function getPortalNodeCreateAction(): PortalNodeCreateActionInterface;

    public function getPortalNodeDeleteAction(): PortalNodeDeleteActionInterface;

    public function getPortalNodeGetAction(): PortalNodeGetActionInterface;

    public function getPortalNodeListAction(): PortalNodeListActionInterface;

    public function getPortalNodeOverviewAction(): PortalNodeOverviewActionInterface;

    public function getRouteCreateAction(): RouteCreateActionInterface;

    public function getRouteFindAction(): RouteFindActionInterface;

    public function getRouteGetAction(): RouteGetActionInterface;

    public function getReceptionRouteListAction(): ReceptionRouteListActionInterface;
}
