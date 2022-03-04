<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Support;

use Heptacom\HeptaConnect\Storage\Base\Bridge\Exception\StorageFacadeServiceException;
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
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationSetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageClearActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageSetActionInterface;
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
        return $this->getInstanceFromContainer(IdentityMapActionInterface::class);
    }

    protected function createIdentityOverviewAction(): IdentityOverviewActionInterface
    {
        return $this->getInstanceFromContainer(IdentityOverviewActionInterface::class);
    }

    protected function createIdentityPersistAction(): IdentityPersistActionInterface
    {
        return $this->getInstanceFromContainer(IdentityPersistActionInterface::class);
    }

    protected function createIdentityReflectAction(): IdentityReflectActionInterface
    {
        return $this->getInstanceFromContainer(IdentityReflectActionInterface::class);
    }

    protected function createJobCreateAction(): JobCreateActionInterface
    {
        return $this->getInstanceFromContainer(JobCreateActionInterface::class);
    }

    protected function createJobDeleteAction(): JobDeleteActionInterface
    {
        return $this->getInstanceFromContainer(JobDeleteActionInterface::class);
    }

    protected function createJobFailAction(): JobFailActionInterface
    {
        return $this->getInstanceFromContainer(JobFailActionInterface::class);
    }

    protected function createJobFinishAction(): JobFinishActionInterface
    {
        return $this->getInstanceFromContainer(JobFinishActionInterface::class);
    }

    protected function createJobGetAction(): JobGetActionInterface
    {
        return $this->getInstanceFromContainer(JobGetActionInterface::class);
    }

    protected function createJobListFinishedAction(): JobListFinishedActionInterface
    {
        return $this->getInstanceFromContainer(JobListFinishedActionInterface::class);
    }

    protected function createJobScheduleAction(): JobScheduleActionInterface
    {
        return $this->getInstanceFromContainer(JobScheduleActionInterface::class);
    }

    protected function createJobStartAction(): JobStartActionInterface
    {
        return $this->getInstanceFromContainer(JobStartActionInterface::class);
    }

    protected function createPortalExtensionActivateAction(): PortalExtensionActivateActionInterface
    {
        return $this->getInstanceFromContainer(PortalExtensionActivateActionInterface::class);
    }

    protected function createPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface
    {
        return $this->getInstanceFromContainer(PortalExtensionDeactivateActionInterface::class);
    }

    protected function createPortalExtensionFindAction(): PortalExtensionFindActionInterface
    {
        return $this->getInstanceFromContainer(PortalExtensionFindActionInterface::class);
    }

    protected function createPortalNodeCreateAction(): PortalNodeCreateActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeCreateActionInterface::class);
    }

    protected function createPortalNodeDeleteAction(): PortalNodeDeleteActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeDeleteActionInterface::class);
    }

    protected function createPortalNodeGetAction(): PortalNodeGetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeGetActionInterface::class);
    }

    protected function createPortalNodeListAction(): PortalNodeListActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeListActionInterface::class);
    }

    protected function createPortalNodeOverviewAction(): PortalNodeOverviewActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeOverviewActionInterface::class);
    }

    protected function createPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeConfigurationGetActionInterface::class);
    }

    protected function createPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeConfigurationSetActionInterface::class);
    }

    protected function createPortalNodeStorageClearAction(): PortalNodeStorageClearActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeStorageClearActionInterface::class);
    }

    protected function createPortalNodeStorageDeleteAction(): PortalNodeStorageDeleteActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeStorageDeleteActionInterface::class);
    }

    protected function createPortalNodeStorageGetAction(): PortalNodeStorageGetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeStorageGetActionInterface::class);
    }

    protected function createPortalNodeStorageSetAction(): PortalNodeStorageSetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeStorageSetActionInterface::class);
    }

    protected function createRouteCreateAction(): RouteCreateActionInterface
    {
        return $this->getInstanceFromContainer(RouteCreateActionInterface::class);
    }

    protected function createRouteDeleteAction(): RouteDeleteActionInterface
    {
        return $this->getInstanceFromContainer(RouteDeleteActionInterface::class);
    }

    protected function createRouteFindAction(): RouteFindActionInterface
    {
        return $this->getInstanceFromContainer(RouteFindActionInterface::class);
    }

    protected function createRouteGetAction(): RouteGetActionInterface
    {
        return $this->getInstanceFromContainer(RouteGetActionInterface::class);
    }

    protected function createReceptionRouteListAction(): ReceptionRouteListActionInterface
    {
        return $this->getInstanceFromContainer(ReceptionRouteListActionInterface::class);
    }

    protected function createRouteOverviewAction(): RouteOverviewActionInterface
    {
        return $this->getInstanceFromContainer(RouteOverviewActionInterface::class);
    }

    protected function createRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface
    {
        return $this->getInstanceFromContainer(RouteCapabilityOverviewActionInterface::class);
    }

    protected function createWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface
    {
        return $this->getInstanceFromContainer(WebHttpHandlerConfigurationFindActionInterface::class);
    }

    protected function createWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface
    {
        return $this->getInstanceFromContainer(WebHttpHandlerConfigurationSetActionInterface::class);
    }

    /**
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    private function getInstanceFromContainer(string $class)
    {
        $result = $this->container->get($class);

        if (!$result instanceof $class) {
            throw new StorageFacadeServiceException($class);
        }

        return $result;
    }
}
