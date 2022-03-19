<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Bridge\Support;

use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeServiceExceptionInterface;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Exception\StorageFacadeServiceException;
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

abstract class AbstractSingletonStorageFacade implements StorageFacadeInterface
{
    private ?IdentityErrorCreateActionInterface $identityErrorCreateAction = null;

    private ?IdentityMapActionInterface $identityMapAction = null;

    private ?IdentityOverviewActionInterface $identityOverviewAction = null;

    private ?IdentityPersistActionInterface $identityPersistAction = null;

    private ?IdentityReflectActionInterface $identityReflectAction = null;

    private ?JobCreateActionInterface $jobCreateAction = null;

    private ?JobDeleteActionInterface $jobDeleteAction = null;

    private ?JobFailActionInterface $jobFailAction = null;

    private ?JobFinishActionInterface $jobFinishAction = null;

    private ?JobGetActionInterface $jobGetAction = null;

    private ?JobListFinishedActionInterface $jobListFinishedAction = null;

    private ?JobScheduleActionInterface $jobScheduleAction = null;

    private ?JobStartActionInterface $jobStartAction = null;

    private ?PortalExtensionActivateActionInterface $portalExtensionActivateAction = null;

    private ?PortalExtensionDeactivateActionInterface $portalExtensionDeactivateAction = null;

    private ?PortalExtensionFindActionInterface $portalExtensionFindAction = null;

    private ?RouteCreateActionInterface $routeCreateAction = null;

    private ?RouteDeleteActionInterface $routeDeleteAction = null;

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

    private ?PortalNodeStorageClearActionInterface $portalNodeStorageClearAction = null;

    private ?PortalNodeStorageDeleteActionInterface $portalNodeStorageDeleteAction = null;

    private ?PortalNodeStorageGetActionInterface $portalNodeStorageGetAction = null;

    private ?PortalNodeStorageListActionInterface $portalNodeStorageListAction = null;

    private ?RouteCapabilityOverviewActionInterface $routeCapabilityOverviewAction = null;

    private ?WebHttpHandlerConfigurationFindActionInterface $webHttpHandlerConfigurationFindAction = null;

    private ?WebHttpHandlerConfigurationSetActionInterface $webHttpHandlerConfigurationSetAction = null;

    public function getIdentityErrorCreateAction(): IdentityErrorCreateActionInterface
    {
        try {
            return $this->identityErrorCreateAction ??= $this->createIdentityErrorCreateAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(IdentityErrorCreateActionInterface::class, $throwable);
        }
    }

    public function getIdentityMapAction(): IdentityMapActionInterface
    {
        try {
            return $this->identityMapAction ??= $this->createIdentityMapAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(IdentityMapActionInterface::class, $throwable);
        }
    }

    public function getIdentityOverviewAction(): IdentityOverviewActionInterface
    {
        try {
            return $this->identityOverviewAction ??= $this->createIdentityOverviewAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(IdentityOverviewActionInterface::class, $throwable);
        }
    }

    public function getIdentityPersistAction(): IdentityPersistActionInterface
    {
        try {
            return $this->identityPersistAction ??= $this->createIdentityPersistAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(IdentityPersistActionInterface::class, $throwable);
        }
    }

    public function getIdentityReflectAction(): IdentityReflectActionInterface
    {
        try {
            return $this->identityReflectAction ??= $this->createIdentityReflectAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(IdentityReflectActionInterface::class, $throwable);
        }
    }

    public function getJobCreateAction(): JobCreateActionInterface
    {
        try {
            return $this->jobCreateAction ??= $this->createJobCreateAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(JobCreateActionInterface::class, $throwable);
        }
    }

    public function getJobDeleteAction(): JobDeleteActionInterface
    {
        try {
            return $this->jobDeleteAction ??= $this->createJobDeleteAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(JobDeleteActionInterface::class, $throwable);
        }
    }

    public function getJobFailAction(): JobFailActionInterface
    {
        try {
            return $this->jobFailAction ??= $this->createJobFailAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(JobFailActionInterface::class, $throwable);
        }
    }

    public function getJobFinishAction(): JobFinishActionInterface
    {
        try {
            return $this->jobFinishAction ??= $this->createJobFinishAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(JobFinishActionInterface::class, $throwable);
        }
    }

    public function getJobGetAction(): JobGetActionInterface
    {
        try {
            return $this->jobGetAction ??= $this->createJobGetAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(JobGetActionInterface::class, $throwable);
        }
    }

    public function getJobListFinishedAction(): JobListFinishedActionInterface
    {
        try {
            return $this->jobListFinishedAction ??= $this->createJobListFinishedAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(JobListFinishedActionInterface::class, $throwable);
        }
    }

    public function getJobScheduleAction(): JobScheduleActionInterface
    {
        try {
            return $this->jobScheduleAction ??= $this->createJobScheduleAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(JobScheduleActionInterface::class, $throwable);
        }
    }

    public function getPortalExtensionActivateAction(): PortalExtensionActivateActionInterface
    {
        try {
            return $this->portalExtensionActivateAction ??= $this->createPortalExtensionActivateAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalExtensionActivateActionInterface::class, $throwable);
        }
    }

    public function getPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface
    {
        try {
            return $this->portalExtensionDeactivateAction ??= $this->createPortalExtensionDeactivateAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalExtensionDeactivateActionInterface::class, $throwable);
        }
    }

    public function getPortalExtensionFindAction(): PortalExtensionFindActionInterface
    {
        try {
            return $this->portalExtensionFindAction ??= $this->createPortalExtensionFindAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalExtensionFindActionInterface::class, $throwable);
        }
    }

    public function getJobStartAction(): JobStartActionInterface
    {
        try {
            return $this->jobStartAction ??= $this->createJobStartAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(JobStartActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeCreateAction(): PortalNodeCreateActionInterface
    {
        try {
            return $this->portalNodeCreateAction ??= $this->createPortalNodeCreateAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeCreateActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeDeleteAction(): PortalNodeDeleteActionInterface
    {
        try {
            return $this->portalNodeDeleteAction ??= $this->createPortalNodeDeleteAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeDeleteActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeGetAction(): PortalNodeGetActionInterface
    {
        try {
            return $this->portalNodeGetAction ??= $this->createPortalNodeGetAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeGetActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeListAction(): PortalNodeListActionInterface
    {
        try {
            return $this->portalNodeListAction ??= $this->createPortalNodeListAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeListActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeOverviewAction(): PortalNodeOverviewActionInterface
    {
        try {
            return $this->portalNodeOverviewAction ??= $this->createPortalNodeOverviewAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeOverviewActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface
    {
        try {
            return $this->portalNodeConfigurationGetAction ??= $this->createPortalNodeConfigurationGetAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeConfigurationGetActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface
    {
        try {
            return $this->portalNodeConfigurationSetAction ??= $this->createPortalNodeConfigurationSetAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeConfigurationSetActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeStorageClearAction(): PortalNodeStorageClearActionInterface
    {
        try {
            return $this->portalNodeStorageClearAction ??= $this->createPortalNodeStorageClearAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeStorageClearActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeStorageDeleteAction(): PortalNodeStorageDeleteActionInterface
    {
        try {
            return $this->portalNodeStorageDeleteAction ??= $this->createPortalNodeStorageDeleteAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeStorageDeleteActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeStorageGetAction(): PortalNodeStorageGetActionInterface
    {
        try {
            return $this->portalNodeStorageGetAction ??= $this->createPortalNodeStorageGetAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeStorageGetActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeStorageListAction(): PortalNodeStorageListActionInterface
    {
        try {
            return $this->portalNodeStorageListAction ??= $this->createPortalNodeStorageListAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeStorageListActionInterface::class, $throwable);
        }
    }

    public function getPortalNodeStorageSetAction(): PortalNodeStorageSetActionInterface
    {
        try {
            return $this->portalNodeStorageSetAction ??= $this->createPortalNodeStorageSetAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(PortalNodeStorageSetActionInterface::class, $throwable);
        }
    }

    public function getRouteCreateAction(): RouteCreateActionInterface
    {
        try {
            return $this->routeCreateAction ??= $this->createRouteCreateAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(RouteCreateActionInterface::class, $throwable);
        }
    }

    public function getRouteDeleteAction(): RouteDeleteActionInterface
    {
        try {
            return $this->routeDeleteAction ??= $this->createRouteDeleteAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(RouteDeleteActionInterface::class, $throwable);
        }
    }

    public function getRouteFindAction(): RouteFindActionInterface
    {
        try {
            return $this->routeFindAction ??= $this->createRouteFindAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(RouteFindActionInterface::class, $throwable);
        }
    }

    public function getRouteGetAction(): RouteGetActionInterface
    {
        try {
            return $this->routeGetAction ??= $this->createRouteGetAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(RouteGetActionInterface::class, $throwable);
        }
    }

    public function getReceptionRouteListAction(): ReceptionRouteListActionInterface
    {
        try {
            return $this->receptionRouteListAction ??= $this->createReceptionRouteListAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(ReceptionRouteListActionInterface::class, $throwable);
        }
    }

    public function getRouteOverviewAction(): RouteOverviewActionInterface
    {
        try {
            return $this->routeOverviewAction ??= $this->createRouteOverviewAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(RouteOverviewActionInterface::class, $throwable);
        }
    }

    public function getRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface
    {
        try {
            return $this->routeCapabilityOverviewAction ??= $this->createRouteCapabilityOverviewAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(RouteCapabilityOverviewActionInterface::class, $throwable);
        }
    }

    public function getWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface
    {
        try {
            return $this->webHttpHandlerConfigurationFindAction ??= $this->createWebHttpHandlerConfigurationFindAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(WebHttpHandlerConfigurationFindActionInterface::class, $throwable);
        }
    }

    public function getWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface
    {
        try {
            return $this->webHttpHandlerConfigurationSetAction ??= $this->createWebHttpHandlerConfigurationSetAction();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            throw $throwable;
        } catch (\Throwable $throwable) {
            throw new StorageFacadeServiceException(WebHttpHandlerConfigurationSetActionInterface::class, $throwable);
        }
    }

    /**
     * @throws \Throwable
     */
    abstract protected function createIdentityErrorCreateAction(): IdentityErrorCreateActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createIdentityMapAction(): IdentityMapActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createIdentityOverviewAction(): IdentityOverviewActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createIdentityPersistAction(): IdentityPersistActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createIdentityReflectAction(): IdentityReflectActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createJobCreateAction(): JobCreateActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createJobDeleteAction(): JobDeleteActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createJobFailAction(): JobFailActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createJobFinishAction(): JobFinishActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createJobGetAction(): JobGetActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createJobListFinishedAction(): JobListFinishedActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createJobScheduleAction(): JobScheduleActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createJobStartAction(): JobStartActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalExtensionActivateAction(): PortalExtensionActivateActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalExtensionDeactivateAction(): PortalExtensionDeactivateActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalExtensionFindAction(): PortalExtensionFindActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeCreateAction(): PortalNodeCreateActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeDeleteAction(): PortalNodeDeleteActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeGetAction(): PortalNodeGetActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeListAction(): PortalNodeListActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeOverviewAction(): PortalNodeOverviewActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeConfigurationGetAction(): PortalNodeConfigurationGetActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeConfigurationSetAction(): PortalNodeConfigurationSetActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeStorageClearAction(): PortalNodeStorageClearActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeStorageDeleteAction(): PortalNodeStorageDeleteActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeStorageGetAction(): PortalNodeStorageGetActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeStorageListAction(): PortalNodeStorageListActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createPortalNodeStorageSetAction(): PortalNodeStorageSetActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createRouteCreateAction(): RouteCreateActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createRouteDeleteAction(): RouteDeleteActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createRouteFindAction(): RouteFindActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createRouteGetAction(): RouteGetActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createReceptionRouteListAction(): ReceptionRouteListActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createRouteOverviewAction(): RouteOverviewActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createRouteCapabilityOverviewAction(): RouteCapabilityOverviewActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createWebHttpHandlerConfigurationFindAction(): WebHttpHandlerConfigurationFindActionInterface;

    /**
     * @throws \Throwable
     */
    abstract protected function createWebHttpHandlerConfigurationSetAction(): WebHttpHandlerConfigurationSetActionInterface;
}
