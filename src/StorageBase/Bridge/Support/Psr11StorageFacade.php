<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Support;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Psr\Container\ContainerInterface;

class Psr11StorageFacade extends AbstractSingletonStorageFacade
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function createPortalNodeCreateAction(): PortalNodeCreateActionInterface
    {
        return $this->container->get(PortalNodeCreateActionInterface::class);
    }

    protected function createPortalNodeDeleteAction(): PortalNodeDeleteActionInterface
    {
        return $this->container->get(PortalNodeDeleteActionInterface::class);
    }

    protected function createPortalNodeGetAction(): PortalNodeGetActionInterface
    {
        return $this->container->get(PortalNodeGetActionInterface::class);
    }

    protected function createPortalNodeListAction(): PortalNodeListActionInterface
    {
        return $this->container->get(PortalNodeListActionInterface::class);
    }

    protected function createPortalNodeOverviewAction(): PortalNodeOverviewActionInterface
    {
        return $this->container->get(PortalNodeOverviewActionInterface::class);
    }

    protected function createRouteCreateAction(): RouteCreateActionInterface
    {
        return $this->container->get(RouteCreateActionInterface::class);
    }

    protected function createRouteFindAction(): RouteFindActionInterface
    {
        return $this->container->get(RouteFindActionInterface::class);
    }

    protected function createRouteGetAction(): RouteGetActionInterface
    {
        return $this->container->get(RouteGetActionInterface::class);
    }

    protected function createReceptionRouteListAction(): ReceptionRouteListActionInterface
    {
        return $this->container->get(ReceptionRouteListActionInterface::class);
    }
}
