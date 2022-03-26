<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Contract;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\FileReference\FileReferenceGetRequestActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\FileReference\FileReferencePersistRequestActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityPersistActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityReflectActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityError\IdentityErrorCreateActionInterface;
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

/**
 * Central storage action providing facade.
 * It is used as strong typed service provider.
 */
interface StorageFacadeInterface
{
    /**
     * Provides storage action to get file reference requests.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getFileReferenceGetRequestAction(): FileReferenceGetRequestActionInterface;

    /**
     * Provides storage action to persist file reference requests.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getFileReferencePersistRequestAction(): FileReferencePersistRequestActionInterface;

    /**
     * Provides storage action to map entities to their identities.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getIdentityErrorCreateAction(): IdentityErrorCreateActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getIdentityMapAction(): IdentityMapActionInterface;

    /**
     * Provides storage action to paginate over all identities.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getIdentityOverviewAction(): IdentityOverviewActionInterface;

    /**
     * Provides storage action to write identities to the storage.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getIdentityPersistAction(): IdentityPersistActionInterface;

    /**
     * Provides storage action to find matching identities from one portal node to another one.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getIdentityReflectAction(): IdentityReflectActionInterface;

    /**
     * Provides storage action to create jobs.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobCreateAction(): JobCreateActionInterface;

    /**
     * Provides storage action to delete jobs.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobDeleteAction(): JobDeleteActionInterface;

    /**
     * Provides storage action to set the job state to failed.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobFailAction(): JobFailActionInterface;

    /**
     * Provides storage action to set the job state to finished.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobFinishAction(): JobFinishActionInterface;

    /**
     * Provides storage action to get job details.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobGetAction(): JobGetActionInterface;

    /**
     * Provides storage action to list finished jobs.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobListFinishedAction(): JobListFinishedActionInterface;

    /**
     * Provides storage action to set the job state to scheduled.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobScheduleAction(): JobScheduleActionInterface;

    /**
     * Provides storage action to set the job state to started.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobStartAction(): JobStartActionInterface;

    /**
     * Provides storage action to activate portal extensions on a portal node.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalExtensionActivateAction(): PortalExtensionActivateActionInterface;

    /**
     * Provides storage action to deactivate portal extensions on a portal node.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface;

    /**
     * Provides storage action to check a portal extension state on a portal node.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalExtensionFindAction(): PortalExtensionFindActionInterface;

    /**
     * Provides storage action to create portal nodes.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeCreateAction(): PortalNodeCreateActionInterface;

    /**
     * Provides storage action to delete portal nodes.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeDeleteAction(): PortalNodeDeleteActionInterface;

    /**
     * Provides storage action to get portal node details.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeGetAction(): PortalNodeGetActionInterface;

    /**
     * Provides storage action to list all portal nodes.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeListAction(): PortalNodeListActionInterface;

    /**
     * Provides storage action to paginate over all portal nodes.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeOverviewAction(): PortalNodeOverviewActionInterface;

    /**
     * Provides storage action to get portal node configuration.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface;

    /**
     * Provides storage action to store portal node configuration.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface;

    /**
     * Provides storage action to clear portal node storages.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeStorageClearAction(): PortalNodeStorageClearActionInterface;

    /**
     * Provides storage action to delete portal node storage entries.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeStorageDeleteAction(): PortalNodeStorageDeleteActionInterface;

    /**
     * Provides storage action to read portal node storage entries.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeStorageGetAction(): PortalNodeStorageGetActionInterface;

    /**
     * Provides storage action to read all portal node storage entries.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeStorageListAction(): PortalNodeStorageListActionInterface;

    /**
     * Provides storage action to write portal node storage entries.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeStorageSetAction(): PortalNodeStorageSetActionInterface;

    /**
     * Provides storage action to create routes between two portal nodes for an entity type.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteCreateAction(): RouteCreateActionInterface;

    /**
     * Provides storage action to delete routes.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteDeleteAction(): RouteDeleteActionInterface;

    /**
     * Provides storage action to find routes by their portal nodes and entity type.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteFindAction(): RouteFindActionInterface;

    /**
     * Provides storage action to get route details.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteGetAction(): RouteGetActionInterface;

    /**
     * Provides storage action to list all routes for a reception scenario.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getReceptionRouteListAction(): ReceptionRouteListActionInterface;

    /**
     * Provides storage action to paginate over all routes.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteOverviewAction(): RouteOverviewActionInterface;

    /**
     * Provides storage action to paginate over all route capabilities.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface;

    /**
     * Provides storage action to get web HTTP handler configuration by portal node and path.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface;

    /**
     * Provides storage action to set web HTTP handler configuration by portal node and path.
     *
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface;
}
