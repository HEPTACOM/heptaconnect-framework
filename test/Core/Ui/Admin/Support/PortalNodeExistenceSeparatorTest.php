<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Support;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Audit\NullAuditTrail;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparator;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\NullAuditTrail
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparationResult
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparator
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException
 */
final class PortalNodeExistenceSeparatorTest extends TestCase
{
    public function testPortalNodeExists(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $testKey = $this->createPortalNodeKey();

        $portalNodeGetAction->expects(static::once())->method('get')->willReturn([
            new PortalNodeGetResult($testKey, FooBarPortal::class()),
        ]);

        $accessor = new PortalNodeExistenceSeparator($portalNodeGetAction);
        $result = $accessor->separateKeys(new PortalNodeKeyCollection([$testKey]));

        static::assertCount(1, $result->getExistingKeys());
        static::assertCount(0, $result->getPreviewKeys());
        static::assertCount(0, $result->getNotFoundKeys());

        $result->throwWhenKeysAreMissing(new NullAuditTrail());
        $result->throwWhenPreviewKeysAreGiven(new NullAuditTrail());
    }

    public function testPortalNodeDoesntExists(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeGetAction->expects(static::once())->method('get')->willReturn([]);

        $accessor = new PortalNodeExistenceSeparator($portalNodeGetAction);
        $result = $accessor->separateKeys(new PortalNodeKeyCollection([$this->createPortalNodeKey()]));

        static::assertCount(0, $result->getExistingKeys());
        static::assertCount(0, $result->getPreviewKeys());
        static::assertCount(1, $result->getNotFoundKeys());

        $result->throwWhenPreviewKeysAreGiven(new NullAuditTrail());

        static::expectException(PortalNodesMissingException::class);
        static::expectExceptionCode(1650732001);

        $result->throwWhenKeysAreMissing(new NullAuditTrail());
    }

    public function testPortalNodeIsPreview(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeGetAction->expects(static::never())->method('get')->willReturn([]);

        $accessor = new PortalNodeExistenceSeparator($portalNodeGetAction);
        $result = $accessor->separateKeys(new PortalNodeKeyCollection([new PreviewPortalNodeKey(FooBarPortal::class())]));

        static::assertCount(0, $result->getExistingKeys());
        static::assertCount(1, $result->getPreviewKeys());
        static::assertCount(0, $result->getNotFoundKeys());

        $result->throwWhenKeysAreMissing(new NullAuditTrail());

        static::expectException(PortalNodesMissingException::class);
        static::expectExceptionCode(1650732002);

        $result->throwWhenPreviewKeysAreGiven(new NullAuditTrail());
    }

    private function createPortalNodeKey(): PortalNodeKeyInterface|MockObject
    {
        $testKey = $this->createMock(PortalNodeKeyInterface::class);
        $testKey->method('equals')->willReturnCallback(static fn ($key): bool => $key === $testKey);

        return $testKey;
    }
}
