<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Support;

use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeOverviewActionInterface;
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

    private ?PortalNodeCreateActionInterface $portalNodeCreateAction = null;

    private ?PortalNodeDeleteActionInterface $portalNodeDeleteAction = null;

    private ?PortalNodeGetActionInterface $portalNodeGetAction = null;

    private ?PortalNodeListActionInterface $portalNodeListAction = null;

    private ?PortalNodeOverviewActionInterface $portalNodeOverviewAction = null;

    public function getPortalNodeCreateAction(): PortalNodeCreateActionInterface
    {
        return $this->portalNodeCreateAction ??= $this->createPortalNodeCreateAction();
    }

    public function getPortalNodeDeleteAction(): PortalNodeDeleteActionInterface
    {
        return $this->portalNodeDeleteAction ??= $this->createPortalNodeDeleteAction();
    }

    public function getPortalNodeGetAction(): PortalNodeGetActionInterface
    {
        return $this->portalNodeGetAction ??= $this->createPortalNodeGetAction();
    }

    public function getPortalNodeListAction(): PortalNodeListActionInterface
    {
        return $this->portalNodeListAction ??= $this->createPortalNodeListAction();
    }

    public function getPortalNodeOverviewAction(): PortalNodeOverviewActionInterface
    {
        return $this->portalNodeOverviewAction ??= $this->createPortalNodeOverviewAction();
    }

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

    abstract protected function createPortalNodeCreateAction(): PortalNodeCreateActionInterface;

    abstract protected function createPortalNodeDeleteAction(): PortalNodeDeleteActionInterface;

    abstract protected function createPortalNodeGetAction(): PortalNodeGetActionInterface;

    abstract protected function createPortalNodeListAction(): PortalNodeListActionInterface;

    abstract protected function createPortalNodeOverviewAction(): PortalNodeOverviewActionInterface;

    abstract protected function createRouteCreateAction(): RouteCreateActionInterface;

    abstract protected function createRouteFindAction(): RouteFindActionInterface;

    abstract protected function createRouteGetAction(): RouteGetActionInterface;

    abstract protected function createReceptionRouteListAction(): ReceptionRouteListActionInterface;
}
