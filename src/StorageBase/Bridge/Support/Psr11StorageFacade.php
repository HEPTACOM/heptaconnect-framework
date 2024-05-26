<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Support;

use Heptacom\HeptaConnect\Storage\Base\Bridge\Exception\StorageFacadeServiceException;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\FileReference\FileReferenceGetRequestActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\FileReference\FileReferencePersistRequestActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityPersistActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityReflectActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityError\IdentityErrorCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityRedirect\IdentityRedirectCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityRedirect\IdentityRedirectDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityRedirect\IdentityRedirectOverviewActionInterface;
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
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageClearActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageListActionInterface;
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
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Psr\Container\ContainerInterface;

class Psr11StorageFacade extends AbstractSingletonStorageFacade
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    #[\Override]
    protected function createFileReferenceGetRequestAction(): FileReferenceGetRequestActionInterface
    {
        return $this->getInstanceFromContainer(FileReferenceGetRequestActionInterface::class);
    }

    #[\Override]
    protected function createFileReferencePersistRequestAction(): FileReferencePersistRequestActionInterface
    {
        return $this->getInstanceFromContainer(FileReferencePersistRequestActionInterface::class);
    }

    #[\Override]
    protected function createIdentityRedirectCreateActionInterface(): IdentityRedirectCreateActionInterface
    {
        return $this->getInstanceFromContainer(IdentityRedirectCreateActionInterface::class);
    }

    #[\Override]
    protected function createIdentityRedirectDeleteActionInterface(): IdentityRedirectDeleteActionInterface
    {
        return $this->getInstanceFromContainer(IdentityRedirectDeleteActionInterface::class);
    }

    #[\Override]
    protected function createIdentityRedirectOverviewActionInterface(): IdentityRedirectOverviewActionInterface
    {
        return $this->getInstanceFromContainer(IdentityRedirectOverviewActionInterface::class);
    }

    #[\Override]
    protected function createIdentityErrorCreateAction(): IdentityErrorCreateActionInterface
    {
        return $this->getInstanceFromContainer(IdentityErrorCreateActionInterface::class);
    }

    #[\Override]
    protected function createIdentityMapAction(): IdentityMapActionInterface
    {
        return $this->getInstanceFromContainer(IdentityMapActionInterface::class);
    }

    #[\Override]
    protected function createIdentityOverviewAction(): IdentityOverviewActionInterface
    {
        return $this->getInstanceFromContainer(IdentityOverviewActionInterface::class);
    }

    #[\Override]
    protected function createIdentityPersistAction(): IdentityPersistActionInterface
    {
        return $this->getInstanceFromContainer(IdentityPersistActionInterface::class);
    }

    #[\Override]
    protected function createIdentityReflectAction(): IdentityReflectActionInterface
    {
        return $this->getInstanceFromContainer(IdentityReflectActionInterface::class);
    }

    #[\Override]
    protected function createJobCreateAction(): JobCreateActionInterface
    {
        return $this->getInstanceFromContainer(JobCreateActionInterface::class);
    }

    #[\Override]
    protected function createJobDeleteAction(): JobDeleteActionInterface
    {
        return $this->getInstanceFromContainer(JobDeleteActionInterface::class);
    }

    #[\Override]
    protected function createJobFailAction(): JobFailActionInterface
    {
        return $this->getInstanceFromContainer(JobFailActionInterface::class);
    }

    #[\Override]
    protected function createJobFinishAction(): JobFinishActionInterface
    {
        return $this->getInstanceFromContainer(JobFinishActionInterface::class);
    }

    #[\Override]
    protected function createJobGetAction(): JobGetActionInterface
    {
        return $this->getInstanceFromContainer(JobGetActionInterface::class);
    }

    #[\Override]
    protected function createJobListFinishedAction(): JobListFinishedActionInterface
    {
        return $this->getInstanceFromContainer(JobListFinishedActionInterface::class);
    }

    #[\Override]
    protected function createJobScheduleAction(): JobScheduleActionInterface
    {
        return $this->getInstanceFromContainer(JobScheduleActionInterface::class);
    }

    #[\Override]
    protected function createJobStartAction(): JobStartActionInterface
    {
        return $this->getInstanceFromContainer(JobStartActionInterface::class);
    }

    #[\Override]
    protected function createPortalExtensionActivateAction(): PortalExtensionActivateActionInterface
    {
        return $this->getInstanceFromContainer(PortalExtensionActivateActionInterface::class);
    }

    #[\Override]
    protected function createPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface
    {
        return $this->getInstanceFromContainer(PortalExtensionDeactivateActionInterface::class);
    }

    #[\Override]
    protected function createPortalExtensionFindAction(): PortalExtensionFindActionInterface
    {
        return $this->getInstanceFromContainer(PortalExtensionFindActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeCreateAction(): PortalNodeCreateActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeCreateActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeDeleteAction(): PortalNodeDeleteActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeDeleteActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeGetAction(): PortalNodeGetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeGetActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeListAction(): PortalNodeListActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeListActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeOverviewAction(): PortalNodeOverviewActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeOverviewActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeAliasGetAction(): PortalNodeAliasGetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeAliasGetActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeAliasFindAction(): PortalNodeAliasFindActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeAliasFindActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeAliasSetAction(): PortalNodeAliasSetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeAliasSetActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeAliasOverviewAction(): PortalNodeAliasOverviewActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeAliasOverviewActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeConfigurationGetActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeConfigurationSetActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeStorageClearAction(): PortalNodeStorageClearActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeStorageClearActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeStorageDeleteAction(): PortalNodeStorageDeleteActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeStorageDeleteActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeStorageGetAction(): PortalNodeStorageGetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeStorageGetActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeStorageListAction(): PortalNodeStorageListActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeStorageListActionInterface::class);
    }

    #[\Override]
    protected function createPortalNodeStorageSetAction(): PortalNodeStorageSetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeStorageSetActionInterface::class);
    }

    #[\Override]
    protected function createRouteCreateAction(): RouteCreateActionInterface
    {
        return $this->getInstanceFromContainer(RouteCreateActionInterface::class);
    }

    #[\Override]
    protected function createRouteDeleteAction(): RouteDeleteActionInterface
    {
        return $this->getInstanceFromContainer(RouteDeleteActionInterface::class);
    }

    #[\Override]
    protected function createRouteFindAction(): RouteFindActionInterface
    {
        return $this->getInstanceFromContainer(RouteFindActionInterface::class);
    }

    #[\Override]
    protected function createRouteGetAction(): RouteGetActionInterface
    {
        return $this->getInstanceFromContainer(RouteGetActionInterface::class);
    }

    #[\Override]
    protected function createReceptionRouteListAction(): ReceptionRouteListActionInterface
    {
        return $this->getInstanceFromContainer(ReceptionRouteListActionInterface::class);
    }

    #[\Override]
    protected function createRouteOverviewAction(): RouteOverviewActionInterface
    {
        return $this->getInstanceFromContainer(RouteOverviewActionInterface::class);
    }

    #[\Override]
    protected function createRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface
    {
        return $this->getInstanceFromContainer(RouteCapabilityOverviewActionInterface::class);
    }

    #[\Override]
    protected function createStorageKeyGenerator(): StorageKeyGeneratorContract
    {
        return $this->getInstanceFromContainer(StorageKeyGeneratorContract::class);
    }

    #[\Override]
    protected function createWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface
    {
        return $this->getInstanceFromContainer(WebHttpHandlerConfigurationFindActionInterface::class);
    }

    #[\Override]
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
