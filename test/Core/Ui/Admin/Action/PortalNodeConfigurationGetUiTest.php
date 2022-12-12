<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeConfigurationGetUi;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeConfigurationGetUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException
 */
final class PortalNodeConfigurationGetUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testReadingWorks(): void
    {
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('equals')->willReturnCallback(static fn (StorageKeyInterface $key): bool => $key === $portalNodeKey);

        $configurationGetAction = $this->createMock(PortalNodeConfigurationGetActionInterface::class);
        $configurationGetAction->method('get')->willReturn([
            new PortalNodeConfigurationGetResult($portalNodeKey, [
                'foobar' => 42,
            ]),
        ]);

        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeGetAction->method('get')->willReturn([
            new PortalNodeGetResult($portalNodeKey, FooBarPortal::class()),
        ]);

        $action = new PortalNodeConfigurationGetUi(
            $this->createAuditTrailFactory(),
            $portalNodeGetAction,
            $configurationGetAction
        );

        $result = \iterable_to_array($action->get(
            new PortalNodeConfigurationGetCriteria(new PortalNodeKeyCollection([$portalNodeKey])),
            $this->createUiActionContext()
        ))[0];

        static::assertSame([
            'foobar' => 42,
        ], $result->getConfiguration());
        static::assertTrue($portalNodeKey->equals($result->getPortalNodeKey()));
    }

    public function testReadingWorksForPreviewPortalNodeKeys(): void
    {
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $configurationGetAction = $this->createMock(PortalNodeConfigurationGetActionInterface::class);
        $configurationGetAction->method('get')->willReturn([
            new PortalNodeConfigurationGetResult($portalNodeKey, [
                'foobar' => 42,
            ]),
        ]);

        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeGetAction->expects(static::never())->method('get');

        $action = new PortalNodeConfigurationGetUi(
            $this->createAuditTrailFactory(),
            $portalNodeGetAction,
            $configurationGetAction
        );

        $result = \iterable_to_array($action->get(
            new PortalNodeConfigurationGetCriteria(new PortalNodeKeyCollection([$portalNodeKey])),
            $this->createUiActionContext()
        ))[0];

        static::assertSame([], $result->getConfiguration());
        static::assertTrue($portalNodeKey->equals($result->getPortalNodeKey()));
    }

    public function testReadingFailsAsPortalNodeIsMissing(): void
    {
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('equals')->willReturnCallback(static fn (StorageKeyInterface $key): bool => $key === $portalNodeKey);

        $configurationGetAction = $this->createMock(PortalNodeConfigurationGetActionInterface::class);
        $configurationGetAction->expects(static::never())->method('get');

        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeGetAction->method('get')->willReturn([]);

        $action = new PortalNodeConfigurationGetUi(
            $this->createAuditTrailFactory(),
            $portalNodeGetAction,
            $configurationGetAction
        );

        static::expectException(PortalNodesMissingException::class);
        static::expectExceptionCode(1670832601);

        \iterable_to_array($action->get(
            new PortalNodeConfigurationGetCriteria(new PortalNodeKeyCollection([$portalNodeKey])),
            $this->createUiActionContext()
        ));
    }

    public function testReadingConfigurationFailsFromStorage(): void
    {
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('equals')->willReturnCallback(static fn (StorageKeyInterface $key): bool => $key === $portalNodeKey);

        $configurationGetAction = $this->createMock(PortalNodeConfigurationGetActionInterface::class);
        $configurationGetAction->method('get')->willThrowException(new \RuntimeException('Reading fails'));

        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeGetAction->method('get')->willReturn([
            new PortalNodeGetResult($portalNodeKey, FooBarPortal::class()),
        ]);

        $action = new PortalNodeConfigurationGetUi(
            $this->createAuditTrailFactory(),
            $portalNodeGetAction,
            $configurationGetAction
        );

        static::expectException(ReadException::class);
        static::expectExceptionCode(1670832602);

        \iterable_to_array($action->get(
            new PortalNodeConfigurationGetCriteria(new PortalNodeKeyCollection([$portalNodeKey])),
            $this->createUiActionContext()
        ));
    }
}
