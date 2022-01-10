<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Contract;

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
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\RouteCapabilityOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationSetActionInterface;

interface StorageFacadeInterface
{
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

    public function getRouteCreateAction(): RouteCreateActionInterface;

    public function getRouteFindAction(): RouteFindActionInterface;

    public function getRouteGetAction(): RouteGetActionInterface;

    public function getReceptionRouteListAction(): ReceptionRouteListActionInterface;

    public function getRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface;

    public function getWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface;

    public function getWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface;
}
