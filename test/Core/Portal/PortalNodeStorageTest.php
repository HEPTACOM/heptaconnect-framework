<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Portal;

use Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory;
use Heptacom\HeptaConnect\Core\Storage\NormalizationRegistry;
use Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableDenormalizer;
use Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableNormalizer;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\Portal;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageClearActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageSetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory
 * @covers \Heptacom\HeptaConnect\Core\Portal\PreviewPortalNodeStorage
 * @covers \Heptacom\HeptaConnect\Core\Storage\NormalizationRegistry
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 */
class PortalNodeStorageTest extends TestCase
{
    public function testRequestsOnPortalNodePreviewKeyAreNotForwardedToStorageImplementation(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $normalizationRegistry = new NormalizationRegistry([
            new SerializableNormalizer(),
        ], [
            new SerializableDenormalizer(),
        ]);
        $deleteAction = $this->createMock(PortalNodeStorageDeleteActionInterface::class);
        $clearAction = $this->createMock(PortalNodeStorageClearActionInterface::class);
        $getAction = $this->createMock(PortalNodeStorageGetActionInterface::class);
        $setAction = $this->createMock(PortalNodeStorageSetActionInterface::class);
        $listAction = $this->createMock(PortalNodeStorageListActionInterface::class);

        $factory = new PortalStorageFactory(
            $normalizationRegistry,
            $clearAction,
            $deleteAction,
            $getAction,
            $listAction,
            $setAction,
            $logger
        );
        $storage = $factory->createPortalStorage(new PreviewPortalNodeKey(Portal::class));

        $deleteAction->expects(static::never())->method('delete');
        $clearAction->expects(static::never())->method('clear');
        $getAction->expects(static::never())->method('get');
        $setAction->expects(static::never())->method('set');
        $listAction->expects(static::never())->method('list');

        $storage->clear();
        $storage->delete('foobar');
        $storage->deleteMultiple(['foobar']);
        $storage->get('foobar');
        \iterable_to_array($storage->getMultiple(['foobar']));
        $storage->has('foobar');
        \iterable_to_array($storage->list());
        $storage->set('foo', 'bar');
        $storage->setMultiple(['foo' => 'bar']);
    }
}
