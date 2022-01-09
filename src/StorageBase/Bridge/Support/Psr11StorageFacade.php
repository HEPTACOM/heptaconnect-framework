<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Support;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFailActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFinishActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobListFinishedActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobScheduleActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobStartActionInterface;
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

    protected function createJobCreateAction(): JobCreateActionInterface
    {
        return $this->container->get(JobCreateActionInterface::class);
    }

    protected function createJobDeleteAction(): JobDeleteActionInterface
    {
        return $this->container->get(JobDeleteActionInterface::class);
    }

    protected function createJobFailAction(): JobFailActionInterface
    {
        return $this->container->get(JobFailActionInterface::class);
    }

    protected function createJobFinishAction(): JobFinishActionInterface
    {
        return $this->container->get(JobFinishActionInterface::class);
    }

    protected function createJobGetAction(): JobGetActionInterface
    {
        return $this->container->get(JobGetActionInterface::class);
    }

    protected function createJobListFinishedAction(): JobListFinishedActionInterface
    {
        return $this->container->get(JobListFinishedActionInterface::class);
    }

    protected function createJobScheduleAction(): JobScheduleActionInterface
    {
        return $this->container->get(JobScheduleActionInterface::class);
    }

    protected function createJobStartAction(): JobStartActionInterface
    {
        return $this->container->get(JobStartActionInterface::class);
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
