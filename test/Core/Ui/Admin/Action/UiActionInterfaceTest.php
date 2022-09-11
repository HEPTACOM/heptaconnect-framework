<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Portal\ComposerPortalLoader;
use Heptacom\HeptaConnect\Core\Portal\Contract\PackageQueryMatcherInterface;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Core\StatusReporting\Contract\StatusReportingServiceInterface;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalEntityListUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeEntityListUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionBrowseUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionDeactivateUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStatusReportUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUiDefault;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionActivateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionDeactivateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias\PortalNodeAliasFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeEntityListUiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalEntityListUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeEntityListUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionBrowseUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionDeactivateUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStatusReportUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUi
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
        $portalNodeEntityListUiAction = $this->createMock(PortalNodeEntityListUiActionInterface::class);
        $portalNodeCreateAction = $this->createMock(PortalNodeCreateActionInterface::class);
        $portalNodeAliasFindAction = $this->createMock(PortalNodeAliasFindActionInterface::class);
        $portalStackServiceContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionActivateAction = $this->createMock(PortalExtensionActivateActionInterface::class);
        $portalExtensionDeactivateAction = $this->createMock(PortalExtensionDeactivateActionInterface::class);
        $packageQueryMatcher = $this->createMock(PackageQueryMatcherInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $statusReportingService = $this->createMock(StatusReportingServiceInterface::class);
        $routeCreateAction = $this->createMock(RouteCreateActionInterface::class);
        $routeFindAction = $this->createMock(RouteFindActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $routeDeleteAction = $this->createMock(RouteDeleteActionInterface::class);

        yield new PortalEntityListUi($portalNodeEntityListUiAction);
        yield new PortalNodeAddUi($portalNodeCreateAction, $portalNodeAliasFindAction);
        yield new PortalNodeEntityListUi($portalStackServiceContainerFactory, $explorerCodeOriginFinder, $emitterCodeOriginFinder, $receiverCodeOriginFinder);
        yield new PortalNodeExtensionActivateUi($portalNodeGetAction, $portalExtensionFindAction, $portalExtensionActivateAction, $packageQueryMatcher, $portalLoader);
        yield new PortalNodeExtensionBrowseUi($portalNodeGetAction, $portalExtensionFindAction, $portalLoader);
        yield new PortalNodeExtensionDeactivateUi($portalNodeGetAction, $portalExtensionFindAction, $portalExtensionDeactivateAction, $packageQueryMatcher, $portalLoader);
        yield new PortalNodeStatusReportUi($statusReportingService);
        yield new RouteAddUiDefault();
        yield new RouteAddUi($routeCreateAction, $routeFindAction, $routeGetAction, $routeDeleteAction, $portalNodeGetAction);
    }
}
