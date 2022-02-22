<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Support;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityPersistActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityReflectActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFailActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFinishActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobListFinishedActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobScheduleActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobStartActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionActivateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionDeactivateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias\PortalNodeAliasFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias\PortalNodeAliasGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias\PortalNodeAliasOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias\PortalNodeAliasSetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationSetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\RouteCapabilityOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationSetActionInterface;
use Psr\Container\ContainerInterface;

class Psr11StorageFacade extends AbstractSingletonStorageFacade
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function createIdentityMapAction(): IdentityMapActionInterface
    {
        return $this->container->get(IdentityMapActionInterface::class);
    }

    protected function createIdentityOverviewAction(): IdentityOverviewActionInterface
    {
        return $this->container->get(IdentityOverviewActionInterface::class);
    }

    protected function createIdentityPersistAction(): IdentityPersistActionInterface
    {
        return $this->container->get(IdentityPersistActionInterface::class);
    }

    protected function createIdentityReflectAction(): IdentityReflectActionInterface
    {
        return $this->container->get(IdentityReflectActionInterface::class);
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

    protected function createPortalExtensionActivateAction(): PortalExtensionActivateActionInterface
    {
        return $this->container->get(PortalExtensionActivateActionInterface::class);
    }

    protected function createPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface
    {
        return $this->container->get(PortalExtensionDeactivateActionInterface::class);
    }

    protected function createPortalExtensionFindAction(): PortalExtensionFindActionInterface
    {
        return $this->container->get(PortalExtensionFindActionInterface::class);
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

    protected function createPortalNodeAliasGetAction(): PortalNodeAliasGetActionInterface
    {
        return $this->container->get(PortalNodeAliasGetActionInterface::class);
    }

    protected function createPortalNodeAliasFindAction(): PortalNodeAliasFindActionInterface
    {
        return $this->container->get(PortalNodeAliasFindActionInterface::class);
    }

    protected function createPortalNodeAliasSetAction(): PortalNodeAliasSetActionInterface
    {
        return $this->container->get(PortalNodeAliasSetActionInterface::class);
    }

    protected function createPortalNodeAliasOverviewAction(): PortalNodeAliasOverviewActionInterface
    {
        return $this->container->get(PortalNodeAliasOverviewActionInterface::class);
    }

    protected function createPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface
    {
        return $this->container->get(PortalNodeConfigurationGetActionInterface::class);
    }

    protected function createPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface
    {
        return $this->container->get(PortalNodeConfigurationSetActionInterface::class);
    }

    protected function createRouteCreateAction(): RouteCreateActionInterface
    {
        return $this->container->get(RouteCreateActionInterface::class);
    }

    protected function createRouteDeleteAction(): RouteDeleteActionInterface
    {
        return $this->container->get(RouteDeleteActionInterface::class);
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

    protected function createRouteOverviewAction(): RouteOverviewActionInterface
    {
        return $this->container->get(RouteOverviewActionInterface::class);
    }

    protected function createRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface
    {
        return $this->container->get(RouteCapabilityOverviewActionInterface::class);
    }

    protected function createWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface
    {
        return $this->container->get(WebHttpHandlerConfigurationFindActionInterface::class);
    }

    protected function createWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface
    {
        return $this->container->get(WebHttpHandlerConfigurationSetActionInterface::class);
    }
}
