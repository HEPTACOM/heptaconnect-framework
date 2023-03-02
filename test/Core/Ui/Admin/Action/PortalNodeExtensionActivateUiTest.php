<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Portal\ComposerPortalLoader;
use Heptacom\HeptaConnect\Core\Portal\Contract\PackageQueryMatcherInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortalExtension;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\Contract\PortalNodeExistenceSeparatorInterface;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparationResult;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparator;
use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionActivateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\NoMatchForPackageQueryException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionsAreAlreadyActiveOnPortalNodeException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparationResult
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparator
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractClassStringReferenceCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\SupportedPortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate\PortalExtensionActivatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Contract\PortalExtensionActiveChangePayloadContract
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\BrowseCriteriaContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNodeExtensionActiveChangePayloadContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\NoMatchForPackageQueryException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionsAreAlreadyActiveOnPortalNodeException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException
 */
final class PortalNodeExtensionActivateUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testPayloadIsAlreadyActive(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionActivateAction = $this->createMock(PortalExtensionActivateActionInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $portalNodeKey = $this->createPortalNodeKey();
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

        $action = new PortalNodeExtensionActivateUi(
            $this->createAuditTrailFactory(),
            new PortalNodeExistenceSeparator($portalNodeGetAction),
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

        $action->activate($payload, $this->createUiActionContext());
    }

    public function testPayloadPortalNodeDoesNotExist(): void
    {
        $portalNodeExistenceSeparator = $this->createMock(PortalNodeExistenceSeparatorInterface::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionActivateAction = $this->createMock(PortalExtensionActivateActionInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $packageQueryMatcher = $this->createMock(PackageQueryMatcherInterface::class);
        $portalExtensionFindResult = new PortalExtensionFindResult();

        $portalExtensionFindResult->add(FooBarPortalExtension::class(), true);
        $portalNodeGetAction->expects(static::never())->method('get');
        $portalNodeExtensionFindAction->method('find')->willReturn($portalExtensionFindResult);
        $portalLoader->method('getPortalExtensions')
            ->willReturn(new PortalExtensionCollection([new FooBarPortalExtension()]));
        $portalNodeExistenceSeparator->method('separateKeys')
            ->willReturn(new PortalNodeExistenceSeparationResult(
                new PortalNodeKeyCollection(),
                new PortalNodeKeyCollection(),
                new PortalNodeKeyCollection([$portalNodeKey]),
            ));

        $action = new PortalNodeExtensionActivateUi(
            $this->createAuditTrailFactory(),
            $portalNodeExistenceSeparator,
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

        self::expectException(PortalNodesMissingException::class);

        $action->activate($payload, $this->createUiActionContext());
    }

    public function testPayloadPortalExtensionNotFound(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalExtensionActivateAction = $this->createMock(PortalExtensionActivateActionInterface::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $portalNodeKey = $this->createPortalNodeKey();
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

        $action = new PortalNodeExtensionActivateUi(
            $this->createAuditTrailFactory(),
            new PortalNodeExistenceSeparator($portalNodeGetAction),
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

        $action->activate($payload, $this->createUiActionContext());
    }

    private function createPortalNodeKey(): PortalNodeKeyInterface|MockObject
    {
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('withoutAlias')->willReturnSelf();
        $portalNodeKey->method('withAlias')->willReturnSelf();
        $portalNodeKey->method('equals')->willReturnCallback(
            static fn (StorageKeyInterface $key): bool => $key === $portalNodeKey
        );

        return $portalNodeKey;
    }
}
