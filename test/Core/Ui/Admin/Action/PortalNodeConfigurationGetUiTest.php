<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeConfigurationGetUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\Contract\PortalNodeExistenceSeparatorInterface;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparationResult;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetResult as UiPortalNodeConfigurationGetResult;
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
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparationResult
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

        $action = new PortalNodeConfigurationGetUi(
            $this->createAuditTrailFactory(),
            $this->createPortalNodeSeparatorAllExists(),
            $configurationGetAction
        );

        $result = \current(\iterable_to_array($action->get(
            new PortalNodeConfigurationGetCriteria(new PortalNodeKeyCollection([$portalNodeKey])),
            $this->createUiActionContext()
        )));

        static::assertInstanceOf(UiPortalNodeConfigurationGetResult::class, $result);
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

        $portalNodeExistenceSeparator = $this->createMock(PortalNodeExistenceSeparatorInterface::class);
        $portalNodeExistenceSeparator->method('separateKeys')->willReturn(new PortalNodeExistenceSeparationResult(
            new PortalNodeKeyCollection([$portalNodeKey]),
            new PortalNodeKeyCollection(),
            new PortalNodeKeyCollection(),
        ));

        $action = new PortalNodeConfigurationGetUi(
            $this->createAuditTrailFactory(),
            $portalNodeExistenceSeparator,
            $configurationGetAction
        );

        $result = \current(\iterable_to_array($action->get(
            new PortalNodeConfigurationGetCriteria(new PortalNodeKeyCollection([$portalNodeKey])),
            $this->createUiActionContext()
        )));

        static::assertInstanceOf(UiPortalNodeConfigurationGetResult::class, $result);
        static::assertSame([], $result->getConfiguration());
        static::assertTrue($portalNodeKey->equals($result->getPortalNodeKey()));
    }

    public function testReadingFailsAsPortalNodeIsMissing(): void
    {
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('equals')->willReturnCallback(static fn (StorageKeyInterface $key): bool => $key === $portalNodeKey);

        $configurationGetAction = $this->createMock(PortalNodeConfigurationGetActionInterface::class);
        $configurationGetAction->expects(static::never())->method('get');

        $action = new PortalNodeConfigurationGetUi(
            $this->createAuditTrailFactory(),
            $this->createPortalNodeSeparatorNoneExists(),
            $configurationGetAction
        );

        static::expectException(PortalNodesMissingException::class);

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

        $action = new PortalNodeConfigurationGetUi(
            $this->createAuditTrailFactory(),
            $this->createPortalNodeSeparatorAllExists(),
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
