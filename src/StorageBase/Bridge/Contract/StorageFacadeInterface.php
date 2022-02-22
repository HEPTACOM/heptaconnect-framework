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

interface StorageFacadeInterface
{
    public function getIdentityMapAction(): IdentityMapActionInterface;

    public function getIdentityOverviewAction(): IdentityOverviewActionInterface;

    public function getIdentityPersistAction(): IdentityPersistActionInterface;

    public function getIdentityReflectAction(): IdentityReflectActionInterface;

    public function getJobCreateAction(): JobCreateActionInterface;

    public function getJobDeleteAction(): JobDeleteActionInterface;

    public function getJobFailAction(): JobFailActionInterface;

    public function getJobFinishAction(): JobFinishActionInterface;

    public function getJobGetAction(): JobGetActionInterface;

    public function getJobListFinishedAction(): JobListFinishedActionInterface;

    public function getJobScheduleAction(): JobScheduleActionInterface;

    public function getJobStartAction(): JobStartActionInterface;

    public function getPortalExtensionActivateAction(): PortalExtensionActivateActionInterface;

    public function getPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface;

    public function getPortalExtensionFindAction(): PortalExtensionFindActionInterface;

    public function getPortalNodeCreateAction(): PortalNodeCreateActionInterface;

    public function getPortalNodeDeleteAction(): PortalNodeDeleteActionInterface;

    public function getPortalNodeGetAction(): PortalNodeGetActionInterface;

    public function getPortalNodeListAction(): PortalNodeListActionInterface;

    public function getPortalNodeOverviewAction(): PortalNodeOverviewActionInterface;

    public function getPortalNodeAliasGetAction(): PortalNodeAliasGetActionInterface;

    public function getPortalNodeAliasFindAction(): PortalNodeAliasFindActionInterface;

    public function getPortalNodeAliasSetAction(): PortalNodeAliasSetActionInterface;

    public function getPortalNodeAliasOverviewAction(): PortalNodeAliasOverviewActionInterface;

    public function getPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface;

    public function getPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface;

    public function getRouteCreateAction(): RouteCreateActionInterface;

    public function getRouteDeleteAction(): RouteDeleteActionInterface;

    public function getRouteFindAction(): RouteFindActionInterface;

    public function getRouteGetAction(): RouteGetActionInterface;

    public function getReceptionRouteListAction(): ReceptionRouteListActionInterface;

    public function getRouteOverviewAction(): RouteOverviewActionInterface;

    public function getRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface;

    public function getWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface;

    public function getWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface;
}
