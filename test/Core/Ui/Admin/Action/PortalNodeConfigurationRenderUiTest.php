<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Configuration\Contract\PortalNodeConfigurationProcessorServiceInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeConfigurationRenderUi;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender\PortalNodeConfigurationRenderCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender\PortalNodeConfigurationRenderResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeConfigurationGetUiActionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeConfigurationRenderUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationGet\PortalNodeConfigurationGetResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender\PortalNodeConfigurationRenderCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeConfigurationRender\PortalNodeConfigurationRenderResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException
 */
final class PortalNodeConfigurationRenderUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testReadingWorks(): void
    {
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('withAlias')->willReturnSelf();
        $portalNodeKey->method('withoutAlias')->willReturnSelf();
        $portalNodeKey->method('equals')->willReturnCallback(static fn (StorageKeyInterface $key): bool => $key === $portalNodeKey);

        $portalNodeConfigGetUiAction = $this->createMock(PortalNodeConfigurationGetUiActionInterface::class);
        $portalNodeConfigGetUiAction->method('get')->willReturn([
            new PortalNodeConfigurationGetResult($portalNodeKey, [
                'foobar' => 42,
            ]),
        ]);

        $configProcessor = $this->createMock(PortalNodeConfigurationProcessorServiceInterface::class);
        $configProcessor->expects(self::once())
            ->method('applyRead')
            ->willReturnCallback(static function (PortalNodeKeyInterface $pnKey, callable $read) use ($portalNodeKey): array {
                static::assertTrue($pnKey->equals($portalNodeKey));

                return $read() + ['default' => 'gizmo'];
            });

        $action = new PortalNodeConfigurationRenderUi(
            $this->createAuditTrailFactory(),
            $portalNodeConfigGetUiAction,
            $configProcessor
        );

        $result = \current(\iterable_to_array($action->getRendered(
            new PortalNodeConfigurationRenderCriteria(new PortalNodeKeyCollection([$portalNodeKey])),
            $this->createUiActionContext()
        )));

        static::assertInstanceOf(PortalNodeConfigurationRenderResult::class, $result);
        static::assertSame([
            'foobar' => 42,
            'default' => 'gizmo',
        ], $result->getConfiguration());
        static::assertTrue($portalNodeKey->equals($result->getPortalNodeKey()));
    }

    public function testReadingWorksForPreviewPortalNodeKeys(): void
    {
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $portalNodeConfigGetUiAction = $this->createMock(PortalNodeConfigurationGetUiActionInterface::class);
        $portalNodeConfigGetUiAction->method('get')->willReturn([
            new PortalNodeConfigurationGetResult($portalNodeKey, []),
        ]);

        $configProcessor = $this->createMock(PortalNodeConfigurationProcessorServiceInterface::class);
        $configProcessor->expects(self::once())
            ->method('applyRead')
            ->willReturnCallback(static function (PortalNodeKeyInterface $pnKey, callable $read) use ($portalNodeKey): array {
                static::assertTrue($pnKey->equals($portalNodeKey));

                return $read() + ['default' => 'gizmo'];
            });

        $action = new PortalNodeConfigurationRenderUi(
            $this->createAuditTrailFactory(),
            $portalNodeConfigGetUiAction,
            $configProcessor
        );

        $result = \current(\iterable_to_array($action->getRendered(
            new PortalNodeConfigurationRenderCriteria(new PortalNodeKeyCollection([$portalNodeKey])),
            $this->createUiActionContext()
        )));

        static::assertInstanceOf(PortalNodeConfigurationRenderResult::class, $result);
        static::assertSame([
            'default' => 'gizmo',
        ], $result->getConfiguration());
        static::assertTrue($portalNodeKey->equals($result->getPortalNodeKey()));
    }
}
