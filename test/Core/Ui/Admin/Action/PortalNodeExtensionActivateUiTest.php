<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Portal\ComposerPortalLoader;
use Heptacom\HeptaConnect\Core\Portal\Contract\PackageQueryMatcherInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortalExtension;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi;
use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionActivateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\NoMatchForPackageQueryException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionsAreAlreadyActiveOnPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeMissingException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection
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
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate\PortalExtensionActivatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\BrowseCriteriaContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\NoMatchForPackageQueryException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionsAreAlreadyActiveOnPortalNodeException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionMissingException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeMissingException
 */
final class PortalNodeExtensionActivateUiTest extends TestCase
{
    public function testPayloadIsAlreadyActive(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionActivateAction = $this->createMock(PortalExtensionActivateActionInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $portalExtensionFindResult = new PortalExtensionFindResult();
        $packageQueryMatcher = $this->createMock(PackageQueryMatcherInterface::class);

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

        $action = new PortalNodeExtensionActivateUi(
            $portalNodeGetAction,
            $portalNodeExtensionFindAction,
            $portalExtensionActivateAction,
            $packageQueryMatcher,
            $portalLoader
        );
        $payload = new PortalNodeExtensionActivatePayload($portalNodeKey);
        $payload->setPortalExtensionQueries(new ClassStringReferenceCollection([
            FooBarPortalExtension::class(),
        ]));

        self::expectException(PortalExtensionsAreAlreadyActiveOnPortalNodeException::class);

        $action->activate($payload);
    }

    public function testPayloadPortalNodeDoesNotExist(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionActivateAction = $this->createMock(PortalExtensionActivateActionInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $portalExtensionFindResult = new PortalExtensionFindResult();
        $packageQueryMatcher = $this->createMock(PackageQueryMatcherInterface::class);

        $portalExtensionFindResult->add(FooBarPortalExtension::class(), true);
        $portalNodeGetAction->method('get')->willReturn([]);
        $portalNodeExtensionFindAction->method('find')->willReturn($portalExtensionFindResult);
        $portalLoader->method('getPortalExtensions')
            ->willReturn(new PortalExtensionCollection([new FooBarPortalExtension()]));

        $action = new PortalNodeExtensionActivateUi(
            $portalNodeGetAction,
            $portalNodeExtensionFindAction,
            $portalExtensionActivateAction,
            $packageQueryMatcher,
            $portalLoader
        );
        $payload = new PortalNodeExtensionActivatePayload($portalNodeKey);
        $payload->setPortalExtensionQueries(new ClassStringReferenceCollection([
            FooBarPortalExtension::class(),
        ]));

        self::expectException(PortalNodeMissingException::class);

        $action->activate($payload);
    }

    public function testPayloadPortalExtensionNotFound(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionActivateAction = $this->createMock(PortalExtensionActivateActionInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $portalExtensionFindResult = new PortalExtensionFindResult();
        $packageQueryMatcher = $this->createMock(PackageQueryMatcherInterface::class);

        $portalExtensionFindResult->add(FooBarPortalExtension::class(), true);
        $portalNodeGetAction->method('get')->willReturn([
            new PortalNodeGetResult($portalNodeKey, FooBarPortal::class()),
        ]);
        $portalNodeExtensionFindAction->method('find')->willReturn($portalExtensionFindResult);
        $portalLoader->method('getPortalExtensions')->willReturn(new PortalExtensionCollection([]));

        $packageQueryMatcher->expects(static::atLeastOnce())
            ->method('matchPortalExtensions')
            ->willReturn(new PortalExtensionCollection());

        $action = new PortalNodeExtensionActivateUi(
            $portalNodeGetAction,
            $portalNodeExtensionFindAction,
            $portalExtensionActivateAction,
            $packageQueryMatcher,
            $portalLoader
        );
        $payload = new PortalNodeExtensionActivatePayload($portalNodeKey);
        $payload->setPortalExtensionQueries(new ClassStringReferenceCollection([
            (new class() extends PortalExtensionContract {
                protected function supports(): string
                {
                    return FooBarPortal::class;
                }
            })::class(),
        ]));

        self::expectException(NoMatchForPackageQueryException::class);

        $action->activate($payload);
    }
}
