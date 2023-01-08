<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Job\Contract\DelegatingJobActorContract;
use Heptacom\HeptaConnect\Core\Portal\ComposerPortalLoader;
use Heptacom\HeptaConnect\Core\Portal\Contract\PackageQueryMatcherInterface;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory;
use Heptacom\HeptaConnect\Core\StatusReporting\Contract\StatusReportingServiceInterface;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\JobRunUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalEntityListUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeEntityListUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionBrowseUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionDeactivateUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeRemoveUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStatusReportUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStorageGetUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUiDefault;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteBrowseUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\Contract\AuditTrailFactoryInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionActivateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionDeactivateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias\PortalNodeAliasFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteOverviewActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeEntityListUiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\JobRunUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalEntityListUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeEntityListUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionBrowseUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionDeactivateUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStatusReportUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStorageGetUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteBrowseUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUiDefault
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 */
final class UiActionInterfaceTest extends TestCase
{
    public function testImplementationAreWellImplemented(): void
    {
        foreach ($this->iterateUiActions() as $uiAction) {
            static::assertTrue($uiAction::class()->isObjectOfType($uiAction));
        }
    }

    /**
     * @return iterable<UiActionInterface>
     */
    private function iterateUiActions(): iterable
    {
        $auditTrailFactory = $this->createMock(AuditTrailFactoryInterface::class);
        $jobActor = $this->createMock(DelegatingJobActorContract::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $portalNodeEntityListUiAction = $this->createMock(PortalNodeEntityListUiActionInterface::class);
        $portalNodeCreateAction = $this->createMock(PortalNodeCreateActionInterface::class);
        $portalNodeAliasFindAction = $this->createMock(PortalNodeAliasFindActionInterface::class);
        $portalStackServiceContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeDeleteAction = $this->createMock(PortalNodeDeleteActionInterface::class);
        $portalExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionActivateAction = $this->createMock(PortalExtensionActivateActionInterface::class);
        $portalExtensionDeactivateAction = $this->createMock(PortalExtensionDeactivateActionInterface::class);
        $packageQueryMatcher = $this->createMock(PackageQueryMatcherInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $statusReportingService = $this->createMock(StatusReportingServiceInterface::class);
        $routeCreateAction = $this->createMock(RouteCreateActionInterface::class);
        $routeDeleteAction = $this->createMock(RouteDeleteActionInterface::class);
        $routeFindAction = $this->createMock(RouteFindActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $routeOverviewAction = $this->createMock(RouteOverviewActionInterface::class);
        $portalStorageFactory = $this->createMock(PortalStorageFactory::class);

        yield new JobRunUi($auditTrailFactory, $jobActor, $jobGetAction);
        yield new PortalEntityListUi($auditTrailFactory, $portalNodeEntityListUiAction);
        yield new PortalNodeAddUi($auditTrailFactory, $portalNodeCreateAction, $portalNodeAliasFindAction);
        yield new PortalNodeEntityListUi($auditTrailFactory, $portalStackServiceContainerFactory, $explorerCodeOriginFinder, $emitterCodeOriginFinder, $receiverCodeOriginFinder);
        yield new PortalNodeExtensionActivateUi($auditTrailFactory, $portalNodeGetAction, $portalExtensionFindAction, $portalExtensionActivateAction, $packageQueryMatcher, $portalLoader);
        yield new PortalNodeExtensionBrowseUi($auditTrailFactory, $portalNodeGetAction, $portalExtensionFindAction, $portalLoader);
        yield new PortalNodeExtensionDeactivateUi($auditTrailFactory, $portalNodeGetAction, $portalExtensionFindAction, $portalExtensionDeactivateAction, $packageQueryMatcher, $portalLoader);
        yield new PortalNodeStatusReportUi($auditTrailFactory, $statusReportingService);
        yield new PortalNodeRemoveUi($auditTrailFactory, $portalNodeGetAction, $portalNodeDeleteAction);
        yield new PortalNodeStorageGetUi($auditTrailFactory, $portalNodeGetAction, $portalStorageFactory);
        yield new RouteAddUiDefault($auditTrailFactory);
        yield new RouteAddUi($auditTrailFactory, $routeCreateAction, $routeFindAction, $routeGetAction, $routeDeleteAction, $portalNodeGetAction);
        yield new RouteBrowseUi($auditTrailFactory, $routeOverviewAction);
    }
}
