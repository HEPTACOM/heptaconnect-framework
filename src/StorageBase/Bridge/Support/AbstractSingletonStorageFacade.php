<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Support;

use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFailActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFinishActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobListFinishedActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobScheduleActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobStartActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Mapping\MappingMapActionInterface;
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
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\RouteCapabilityOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationSetActionInterface;

abstract class AbstractSingletonStorageFacade implements StorageFacadeInterface
{
    private ?JobCreateActionInterface $jobCreateAction = null;

    private ?JobDeleteActionInterface $jobDeleteAction = null;

    private ?JobFailActionInterface $jobFailAction = null;

    private ?JobFinishActionInterface $jobFinishAction = null;

    private ?JobGetActionInterface $jobGetAction = null;

    private ?JobListFinishedActionInterface $jobListFinishedAction = null;

    private ?JobScheduleActionInterface $jobScheduleAction = null;

    private ?JobStartActionInterface $jobStartAction = null;

    private ?MappingMapActionInterface $mappingMapAction = null;

    private ?PortalExtensionActivateActionInterface $portalExtensionActivateAction = null;

    private ?PortalExtensionDeactivateActionInterface $portalExtensionDeactivateAction = null;

    private ?PortalExtensionFindActionInterface $portalExtensionFindAction = null;

    private ?RouteCreateActionInterface $routeCreateAction = null;

    private ?RouteFindActionInterface $routeFindAction = null;

    private ?RouteGetActionInterface $routeGetAction = null;

    private ?RouteOverviewActionInterface $routeOverviewAction = null;

    private ?ReceptionRouteListActionInterface $receptionRouteListAction = null;

    private ?PortalNodeCreateActionInterface $portalNodeCreateAction = null;

    private ?PortalNodeDeleteActionInterface $portalNodeDeleteAction = null;

    private ?PortalNodeGetActionInterface $portalNodeGetAction = null;

    private ?PortalNodeListActionInterface $portalNodeListAction = null;

    private ?PortalNodeOverviewActionInterface $portalNodeOverviewAction = null;

    private ?PortalNodeConfigurationGetActionInterface $portalNodeConfigurationGetAction = null;

    private ?PortalNodeConfigurationSetActionInterface $portalNodeConfigurationSetAction = null;

    private ?RouteCapabilityOverviewActionInterface $routeCapabilityOverviewAction = null;

    private ?WebHttpHandlerConfigurationFindActionInterface $webHttpHandlerConfigurationFindAction = null;

    private ?WebHttpHandlerConfigurationSetActionInterface $webHttpHandlerConfigurationSetAction = null;

    public function getJobCreateAction(): JobCreateActionInterface
    {
        return $this->jobCreateAction ??= $this->createJobCreateAction();
    }

    public function getJobDeleteAction(): JobDeleteActionInterface
    {
        return $this->jobDeleteAction ??= $this->createJobDeleteAction();
    }

    public function getJobFailAction(): JobFailActionInterface
    {
        return $this->jobFailAction ??= $this->createJobFailAction();
    }

    public function getJobFinishAction(): JobFinishActionInterface
    {
        return $this->jobFinishAction ??= $this->createJobFinishAction();
    }

    public function getJobGetAction(): JobGetActionInterface
    {
        return $this->jobGetAction ??= $this->createJobGetAction();
    }

    public function getJobListFinishedAction(): JobListFinishedActionInterface
    {
        return $this->jobListFinishedAction ??= $this->createJobListFinishedAction();
    }

    public function getJobScheduleAction(): JobScheduleActionInterface
    {
        return $this->jobScheduleAction ??= $this->createJobScheduleAction();
    }

    public function getMappingMapAction(): MappingMapActionInterface
    {
        return $this->mappingMapAction ??= $this->createMappingMapAction();
    }

    public function getPortalExtensionActivateAction(): PortalExtensionActivateActionInterface
    {
        return $this->portalExtensionActivateAction ??= $this->createPortalExtensionActivateAction();
    }

    public function getPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface
    {
        return $this->portalExtensionDeactivateAction ??= $this->createPortalExtensionDeactivateAction();
    }

    public function getPortalExtensionFindAction(): PortalExtensionFindActionInterface
    {
        return $this->portalExtensionFindAction ??= $this->createPortalExtensionFindAction();
    }

    public function getJobStartAction(): JobStartActionInterface
    {
        return $this->jobStartAction ??= $this->createJobStartAction();
    }

    public function getPortalNodeCreateAction(): PortalNodeCreateActionInterface
    {
        return $this->portalNodeCreateAction ??= $this->createPortalNodeCreateAction();
    }

    public function getPortalNodeDeleteAction(): PortalNodeDeleteActionInterface
    {
        return $this->portalNodeDeleteAction ??= $this->createPortalNodeDeleteAction();
    }

    public function getPortalNodeGetAction(): PortalNodeGetActionInterface
    {
        return $this->portalNodeGetAction ??= $this->createPortalNodeGetAction();
    }

    public function getPortalNodeListAction(): PortalNodeListActionInterface
    {
        return $this->portalNodeListAction ??= $this->createPortalNodeListAction();
    }

    public function getPortalNodeOverviewAction(): PortalNodeOverviewActionInterface
    {
        return $this->portalNodeOverviewAction ??= $this->createPortalNodeOverviewAction();
    }

    public function getPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface
    {
        return $this->portalNodeConfigurationGetAction ??= $this->createPortalNodeConfigurationGetAction();
    }

    public function getPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface
    {
        return $this->portalNodeConfigurationSetAction ??= $this->createPortalNodeConfigurationSetAction();
    }

    public function getRouteCreateAction(): RouteCreateActionInterface
    {
        return $this->routeCreateAction ??= $this->createRouteCreateAction();
    }

    public function getRouteFindAction(): RouteFindActionInterface
    {
        return $this->routeFindAction ??= $this->createRouteFindAction();
    }

    public function getRouteGetAction(): RouteGetActionInterface
    {
        return $this->routeGetAction ??= $this->createRouteGetAction();
    }

    public function getReceptionRouteListAction(): ReceptionRouteListActionInterface
    {
        return $this->receptionRouteListAction ??= $this->createReceptionRouteListAction();
    }

    public function getRouteOverviewAction(): RouteOverviewActionInterface
    {
        return $this->routeOverviewAction ??= $this->createRouteOverviewAction();
    }

    public function getRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface
    {
        return $this->routeCapabilityOverviewAction ??= $this->createRouteCapabilityOverviewAction();
    }

    public function getWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface
    {
        return $this->webHttpHandlerConfigurationFindAction ??= $this->createWebHttpHandlerConfigurationFindAction();
    }

    public function getWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface
    {
        return $this->webHttpHandlerConfigurationSetAction ??= $this->createWebHttpHandlerConfigurationSetAction();
    }

    abstract protected function createJobCreateAction(): JobCreateActionInterface;

    abstract protected function createJobDeleteAction(): JobDeleteActionInterface;

    abstract protected function createJobFailAction(): JobFailActionInterface;

    abstract protected function createJobFinishAction(): JobFinishActionInterface;

    abstract protected function createJobGetAction(): JobGetActionInterface;

    abstract protected function createJobListFinishedAction(): JobListFinishedActionInterface;

    abstract protected function createJobScheduleAction(): JobScheduleActionInterface;

    abstract protected function createJobStartAction(): JobStartActionInterface;

    abstract protected function createMappingMapAction(): MappingMapActionInterface;

    abstract protected function createPortalExtensionActivateAction(): PortalExtensionActivateActionInterface;

    abstract protected function createPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface;

    abstract protected function createPortalExtensionFindAction(): PortalExtensionFindActionInterface;

    abstract protected function createPortalNodeCreateAction(): PortalNodeCreateActionInterface;

    abstract protected function createPortalNodeDeleteAction(): PortalNodeDeleteActionInterface;

    abstract protected function createPortalNodeGetAction(): PortalNodeGetActionInterface;

    abstract protected function createPortalNodeListAction(): PortalNodeListActionInterface;

    abstract protected function createPortalNodeOverviewAction(): PortalNodeOverviewActionInterface;

    abstract protected function createPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface;

    abstract protected function createPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface;

    abstract protected function createRouteCreateAction(): RouteCreateActionInterface;

    abstract protected function createRouteFindAction(): RouteFindActionInterface;

    abstract protected function createRouteGetAction(): RouteGetActionInterface;

    abstract protected function createReceptionRouteListAction(): ReceptionRouteListActionInterface;

    abstract protected function createRouteOverviewAction(): RouteOverviewActionInterface;

    abstract protected function createRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface;

    abstract protected function createWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface;

    abstract protected function createWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface;
}
