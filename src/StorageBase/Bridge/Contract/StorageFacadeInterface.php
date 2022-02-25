<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Contract;

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
     * Provides storage action to map entities to their identities.
     */
    public function getIdentityMapAction(): IdentityMapActionInterface;

    /**
     * Provides storage action to paginate over all identities.
     */
    public function getIdentityOverviewAction(): IdentityOverviewActionInterface;

    /**
     * Provides storage action to write identities to the storage.
     */
    public function getIdentityPersistAction(): IdentityPersistActionInterface;

    /**
     * Provides storage action to find matching identities from one portal node to another one.
     */
    public function getIdentityReflectAction(): IdentityReflectActionInterface;

    /**
     * Provides storage action to create jobs.
     */
    public function getJobCreateAction(): JobCreateActionInterface;

    /**
     * Provides storage action to delete jobs.
     */
    public function getJobDeleteAction(): JobDeleteActionInterface;

    /**
     * Provides storage action to set the job state to failed.
     */
    public function getJobFailAction(): JobFailActionInterface;

    /**
     * Provides storage action to set the job state to finished.
     */
    public function getJobFinishAction(): JobFinishActionInterface;

    /**
     * Provides storage action to get job details.
     */
    public function getJobGetAction(): JobGetActionInterface;

    /**
     * Provides storage action to list finished jobs.
     */
    public function getJobListFinishedAction(): JobListFinishedActionInterface;

    /**
     * Provides storage action to set the job state to scheduled.
     */
    public function getJobScheduleAction(): JobScheduleActionInterface;

    /**
     * Provides storage action to set the job state to started.
     */
    public function getJobStartAction(): JobStartActionInterface;

    /**
     * Provides storage action to activate portal extensions on a portal node.
     */
    public function getPortalExtensionActivateAction(): PortalExtensionActivateActionInterface;

    /**
     * Provides storage action to deactivate portal extensions on a portal node.
     */
    public function getPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface;

    /**
     * Provides storage action to check a portal extension state on a portal node.
     */
    public function getPortalExtensionFindAction(): PortalExtensionFindActionInterface;

    /**
     * Provides storage action to create portal nodes.
     */
    public function getPortalNodeCreateAction(): PortalNodeCreateActionInterface;

    /**
     * Provides storage action to delete portal nodes.
     */
    public function getPortalNodeDeleteAction(): PortalNodeDeleteActionInterface;

    /**
     * Provides storage action to get portal node details.
     */
    public function getPortalNodeGetAction(): PortalNodeGetActionInterface;

    /**
     * Provides storage action to list all portal nodes.
     */
    public function getPortalNodeListAction(): PortalNodeListActionInterface;

    /**
     * Provides storage action to paginate over all portal nodes.
     */
    public function getPortalNodeOverviewAction(): PortalNodeOverviewActionInterface;

    /**
     * Provides storage action to get portal node configuration.
     */
    public function getPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface;

    /**
     * Provides storage action to store portal node configuration.
     */
    public function getPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface;

    /**
     * Provides storage action to create routes between two portals for an entity type.
     */
    public function getRouteCreateAction(): RouteCreateActionInterface;

    /**
     * Provides storage action to delete routes.
     */
    public function getRouteDeleteAction(): RouteDeleteActionInterface;

    /**
     * Provides storage action to find routes by their portal nodes and entity type.
     */
    public function getRouteFindAction(): RouteFindActionInterface;

    /**
     * Provides storage action to get route details.
     */
    public function getRouteGetAction(): RouteGetActionInterface;

    /**
     * Provides storage action to list all routes for a reception scenario.
     */
    public function getReceptionRouteListAction(): ReceptionRouteListActionInterface;

    /**
     * Provides storage action to paginate over all routes.
     */
    public function getRouteOverviewAction(): RouteOverviewActionInterface;

    /**
     * Provides storage action to paginate over all route capabilities.
     */
    public function getRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface;

    /**
     * Provides storage action to get web http handler configuration by portal node and path.
     */
    public function getWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface;

    /**
     * Provides storage action to set web http handler configuration by portal node and path.
     */
    public function getWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface;
}
