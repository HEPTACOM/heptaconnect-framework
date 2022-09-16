<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Portal\ComposerPortalLoader;
use Heptacom\HeptaConnect\Core\Portal\Contract\PackageQueryMatcherInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortalExtension;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionDeactivateUi;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate\PortalExtensionDeactivateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionDeactivateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionDeactivate\PortalNodeExtensionDeactivatePayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\NoMatchForPackageQueryException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionsAreAlreadyInactiveOnPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionDeactivateUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractClassStringReferenceCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\SupportedPortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate\PortalExtensionDeactivatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate\PortalExtensionDeactivateResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionDeactivate\PortalNodeExtensionDeactivatePayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\BrowseCriteriaContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\NoMatchForPackageQueryException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionsAreAlreadyInactiveOnPortalNodeException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException
 */
final class PortalNodeExtensionDeactivateUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testPayloadIsAlreadyInactive(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionDeactivateAction = $this->createMock(PortalExtensionDeactivateActionInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $packageQueryMatcher = $this->createMock(PackageQueryMatcherInterface::class);
        $portalExtensionFindResult = new PortalExtensionFindResult();

        $portalExtensionFindResult->add(FooBarPortalExtension::class(), false);
        $portalNodeGetAction->method('get')->willReturn([
            new PortalNodeGetResult($portalNodeKey, FooBarPortal::class()),
        ]);
        $portalNodeExtensionFindAction->method('find')->willReturn($portalExtensionFindResult);
        $portalLoader->method('getPortalExtensions')
            ->willReturn(new PortalExtensionCollection([new FooBarPortalExtension()]));
        $packageQueryMatcher->expects(static::atLeastOnce())
            ->method('matchPortalExtensions')
            ->willReturnArgument(1);

        $action = new PortalNodeExtensionDeactivateUi(
            $this->createAuditTrailFactory(),
            $portalNodeGetAction,
            $portalNodeExtensionFindAction,
            $portalExtensionDeactivateAction,
            $packageQueryMatcher,
            $portalLoader
        );
        $payload = new PortalNodeExtensionDeactivatePayload($portalNodeKey);
        $payload->setPortalExtensionQueries([
            FooBarPortalExtension::class,
        ]);

        self::expectException(PortalExtensionsAreAlreadyInactiveOnPortalNodeException::class);

        $action->deactivate($payload, $this->createUiActionContext());
    }

    public function testPayloadPortalNodeDoesNotExist(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionDeactivateAction = $this->createMock(PortalExtensionDeactivateActionInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $packageQueryMatcher = $this->createMock(PackageQueryMatcherInterface::class);
        $portalExtensionFindResult = new PortalExtensionFindResult();

        $portalExtensionFindResult->add(FooBarPortalExtension::class(), true);
        $portalNodeGetAction->method('get')->willReturn([]);
        $portalNodeExtensionFindAction->method('find')->willReturn($portalExtensionFindResult);
        $portalLoader->method('getPortalExtensions')
            ->willReturn(new PortalExtensionCollection([new FooBarPortalExtension()]));

        $action = new PortalNodeExtensionDeactivateUi(
            $this->createAuditTrailFactory(),
            $portalNodeGetAction,
            $portalNodeExtensionFindAction,
            $portalExtensionDeactivateAction,
            $packageQueryMatcher,
            $portalLoader
        );
        $payload = new PortalNodeExtensionDeactivatePayload($portalNodeKey);
        $payload->setPortalExtensionQueries([
            FooBarPortalExtension::class,
        ]);

        self::expectException(PortalNodesMissingException::class);

        $action->deactivate($payload, $this->createUiActionContext());
    }

    public function testPayloadPortalExtensionDoesNotExist(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionDeactivateAction = $this->createMock(PortalExtensionDeactivateActionInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $packageQueryMatcher = $this->createMock(PackageQueryMatcherInterface::class);
        $portalExtensionFindResult = new PortalExtensionFindResult();

        $portalExtensionFindResult->add(FooBarPortalExtension::class(), true);
        $portalNodeGetAction->method('get')->willReturn([
            new PortalNodeGetResult($portalNodeKey, FooBarPortal::class()),
        ]);
        $portalNodeExtensionFindAction->method('find')->willReturn($portalExtensionFindResult);
        $portalLoader->method('getPortalExtensions')->willReturn(new PortalExtensionCollection([]));
        $packageQueryMatcher->expects(static::atLeastOnce())
            ->method('matchPortalExtensions')
            ->willReturn(new PortalExtensionCollection());

        $action = new PortalNodeExtensionDeactivateUi(
            $this->createAuditTrailFactory(),
            $portalNodeGetAction,
            $portalNodeExtensionFindAction,
            $portalExtensionDeactivateAction,
            $packageQueryMatcher,
            $portalLoader
        );
        $payload = new PortalNodeExtensionDeactivatePayload($portalNodeKey);
        $payload->setPortalExtensionQueries([
            'A\\Class\\That\\Does\\Not\\Exist',
        ]);

        self::expectException(NoMatchForPackageQueryException::class);

        $action->deactivate($payload, $this->createUiActionContext());
    }

    public function testPayloadBecomesInActive(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionDeactivateAction = $this->createMock(PortalExtensionDeactivateActionInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $packageQueryMatcher = $this->createMock(PackageQueryMatcherInterface::class);
        $portalExtensionFindResult = new PortalExtensionFindResult();

        $portalExtensionFindResult->add(FooBarPortalExtension::class(), true);
        $portalNodeGetAction->method('get')->willReturn([
            new PortalNodeGetResult($portalNodeKey, FooBarPortal::class()),
        ]);
        $portalNodeExtensionFindAction->method('find')->willReturn($portalExtensionFindResult);
        $portalLoader->method('getPortalExtensions')
            ->willReturn(new PortalExtensionCollection([new FooBarPortalExtension()]));
        $packageQueryMatcher->expects(static::atLeastOnce())
            ->method('matchPortalExtensions')
            ->willReturnArgument(1);
        $portalExtensionDeactivateAction->expects(static::once())
            ->method('deactivate')
            ->willReturn(new PortalExtensionDeactivateResult(
                new PortalExtensionTypeCollection([FooBarPortalExtension::class()]),
                new PortalExtensionTypeCollection()
            ));

        $action = new PortalNodeExtensionDeactivateUi(
            $this->createAuditTrailFactory(),
            $portalNodeGetAction,
            $portalNodeExtensionFindAction,
            $portalExtensionDeactivateAction,
            $packageQueryMatcher,
            $portalLoader
        );
        $payload = new PortalNodeExtensionDeactivatePayload($portalNodeKey);
        $payload->setPortalExtensionQueries([
            FooBarPortalExtension::class,
        ]);

        $action->deactivate($payload, $this->createUiActionContext());
    }
}
