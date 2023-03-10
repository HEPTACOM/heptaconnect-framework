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
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail\UiAuditTrailBeginActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail\UiAuditTrailEndActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail\UiAuditTrailLogErrorActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail\UiAuditTrailLogOutputActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationSetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Psr\Container\ContainerInterface;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Psr11StorageFacade extends AbstractSingletonStorageFacade
{
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    protected function createFileReferenceGetRequestAction(): FileReferenceGetRequestActionInterface
    {
        return $this->getInstanceFromContainer(FileReferenceGetRequestActionInterface::class);
    }

    protected function createFileReferencePersistRequestAction(): FileReferencePersistRequestActionInterface
    {
        return $this->getInstanceFromContainer(FileReferencePersistRequestActionInterface::class);
    }

    protected function createIdentityRedirectCreateActionInterface(): IdentityRedirectCreateActionInterface
    {
        return $this->getInstanceFromContainer(IdentityRedirectCreateActionInterface::class);
    }

    protected function createIdentityRedirectDeleteActionInterface(): IdentityRedirectDeleteActionInterface
    {
        return $this->getInstanceFromContainer(IdentityRedirectDeleteActionInterface::class);
    }

    protected function createIdentityRedirectOverviewActionInterface(): IdentityRedirectOverviewActionInterface
    {
        return $this->getInstanceFromContainer(IdentityRedirectOverviewActionInterface::class);
    }

    protected function createIdentityErrorCreateAction(): IdentityErrorCreateActionInterface
    {
        return $this->getInstanceFromContainer(IdentityErrorCreateActionInterface::class);
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

    protected function createPortalNodeAliasGetAction(): PortalNodeAliasGetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeAliasGetActionInterface::class);
    }

    protected function createPortalNodeAliasFindAction(): PortalNodeAliasFindActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeAliasFindActionInterface::class);
    }

    protected function createPortalNodeAliasSetAction(): PortalNodeAliasSetActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeAliasSetActionInterface::class);
    }

    protected function createPortalNodeAliasOverviewAction(): PortalNodeAliasOverviewActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeAliasOverviewActionInterface::class);
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

    protected function createPortalNodeStorageListAction(): PortalNodeStorageListActionInterface
    {
        return $this->getInstanceFromContainer(PortalNodeStorageListActionInterface::class);
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

    protected function createStorageKeyGenerator(): StorageKeyGeneratorContract
    {
        return $this->getInstanceFromContainer(StorageKeyGeneratorContract::class);
    }

    protected function createUiAuditTrailBeginAction(): UiAuditTrailBeginActionInterface
    {
        return $this->getInstanceFromContainer(UiAuditTrailBeginActionInterface::class);
    }

    protected function createUiAuditTrailLogOutputAction(): UiAuditTrailLogOutputActionInterface
    {
        return $this->getInstanceFromContainer(UiAuditTrailLogOutputActionInterface::class);
    }

    protected function createUiAuditTrailLogErrorAction(): UiAuditTrailLogErrorActionInterface
    {
        return $this->getInstanceFromContainer(UiAuditTrailLogErrorActionInterface::class);
    }

    protected function createUiAuditTrailEndAction(): UiAuditTrailEndActionInterface
    {
        return $this->getInstanceFromContainer(UiAuditTrailEndActionInterface::class);
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
