<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Support;

use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;

abstract class AbstractSingletonStorageFacade implements StorageFacadeInterface
{
    private ?RouteCreateActionInterface $routeCreateAction = null;

    private ?RouteFindActionInterface $routeFindAction = null;

    private ?RouteGetActionInterface $routeGetAction = null;

    private ?ReceptionRouteListActionInterface $receptionRouteListAction = null;

    public function getRouteCreateAction(): RouteCreateActionInterface
    {
        return $this->routeCreateAction ??= $this->createRouteCreateAction();
    }

    public function getRouteFindAction(): RouteFindActionInterface
    {
        return $this->routeFindAction ??= $this->createRouteFindAction();
    }

    public function getRouteGetAction(): RouteGetActionInterface
    {
        return $this->routeGetAction ??= $this->createRouteGetAction();
    }

    public function getReceptionRouteListAction(): ReceptionRouteListActionInterface
    {
        return $this->receptionRouteListAction ??= $this->createReceptionRouteListAction();
    }

    abstract protected function createRouteCreateAction(): RouteCreateActionInterface;

    abstract protected function createRouteFindAction(): RouteFindActionInterface;

    abstract protected function createRouteGetAction(): RouteGetActionInterface;

    abstract protected function createReceptionRouteListAction(): ReceptionRouteListActionInterface;
}
