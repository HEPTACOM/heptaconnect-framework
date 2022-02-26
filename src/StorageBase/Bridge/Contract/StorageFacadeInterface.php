<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Contract;

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
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\RouteCapabilityOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationSetActionInterface;

interface StorageFacadeInterface
{
    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getIdentityErrorCreateAction(): IdentityErrorCreateActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getIdentityMapAction(): IdentityMapActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getIdentityOverviewAction(): IdentityOverviewActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getIdentityPersistAction(): IdentityPersistActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getIdentityReflectAction(): IdentityReflectActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobCreateAction(): JobCreateActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobDeleteAction(): JobDeleteActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobFailAction(): JobFailActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobFinishAction(): JobFinishActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobGetAction(): JobGetActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobListFinishedAction(): JobListFinishedActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobScheduleAction(): JobScheduleActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getJobStartAction(): JobStartActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalExtensionActivateAction(): PortalExtensionActivateActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalExtensionFindAction(): PortalExtensionFindActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeCreateAction(): PortalNodeCreateActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeDeleteAction(): PortalNodeDeleteActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeGetAction(): PortalNodeGetActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeListAction(): PortalNodeListActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeOverviewAction(): PortalNodeOverviewActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteCreateAction(): RouteCreateActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteDeleteAction(): RouteDeleteActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteFindAction(): RouteFindActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteGetAction(): RouteGetActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getReceptionRouteListAction(): ReceptionRouteListActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteOverviewAction(): RouteOverviewActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface;

    /**
     * @throws StorageFacadeServiceExceptionInterface
     */
    public function getWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface;
}
