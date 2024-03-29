<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Portal;

use Heptacom\HeptaConnect\Core\Portal\PortalStorage;
use Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory;
use Heptacom\HeptaConnect\Core\Portal\Storage\PortalNodeStorageItemPacker;
use Heptacom\HeptaConnect\Core\Portal\Storage\PortalNodeStorageItemUnpacker;
use Heptacom\HeptaConnect\Core\Storage\NormalizationRegistry;
use Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableDenormalizer;
use Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableNormalizer;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\Portal;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Listing\PortalNodeStorageListResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageClearActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageSetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers \Heptacom\HeptaConnect\Core\Portal\PortalStorage
 * @covers \Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory
 * @covers \Heptacom\HeptaConnect\Core\Portal\PreviewPortalNodeStorage
 * @covers \Heptacom\HeptaConnect\Core\Portal\Storage\PortalNodeStorageItemPacker
 * @covers \Heptacom\HeptaConnect\Core\Portal\Storage\PortalNodeStorageItemUnpacker
 * @covers \Heptacom\HeptaConnect\Core\Storage\NormalizationRegistry
 * @covers \Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableDenormalizer
 * @covers \Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableNormalizer
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Listing\PortalNodeStorageListCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\PortalNodeStorageItemContract
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
            new PortalNodeStorageItemPacker($normalizationRegistry, $logger),
            new PortalNodeStorageItemUnpacker($normalizationRegistry, $logger),
            $clearAction,
            $deleteAction,
            $getAction,
            $listAction,
            $setAction,
            $logger
        );
        $storage = $factory->createPortalStorage(new PreviewPortalNodeKey(Portal::class()));

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

    public function testListWillContinueReturnEntriesEvenIfOneCanNotBeParsed(): void
    {
        $logger = new NullLogger();
        $normalizer = new SerializableNormalizer();
        $normalizationRegistry = new NormalizationRegistry([
            $normalizer,
        ], [
            new SerializableDenormalizer(),
        ]);
        $deleteAction = $this->createMock(PortalNodeStorageDeleteActionInterface::class);
        $clearAction = $this->createMock(PortalNodeStorageClearActionInterface::class);
        $getAction = $this->createMock(PortalNodeStorageGetActionInterface::class);
        $setAction = $this->createMock(PortalNodeStorageSetActionInterface::class);
        $listAction = $this->createMock(PortalNodeStorageListActionInterface::class);
        // invalid example key, but this is not important
        $portalNodeKey = new PreviewPortalNodeKey(Portal::class());

        $storage = new PortalStorage(
            new PortalNodeStorageItemPacker($normalizationRegistry, $logger),
            new PortalNodeStorageItemUnpacker($normalizationRegistry, $logger),
            $clearAction,
            $deleteAction,
            $getAction,
            $listAction,
            $setAction,
            $logger,
            $portalNodeKey
        );

        $deleteAction->expects(static::never())->method('delete');
        $clearAction->expects(static::never())->method('clear');
        $getAction->expects(static::never())->method('get');
        $setAction->expects(static::never())->method('set');
        $listAction->expects(static::once())
            ->method('list')
            ->willReturn([
                new PortalNodeStorageListResult($portalNodeKey, 'key-a', $normalizer->getType(), $normalizer->normalize('value-a')),
                new PortalNodeStorageListResult($portalNodeKey, 'key-b', $normalizer->getType(), $normalizer->normalize('value-b')),
                new PortalNodeStorageListResult($portalNodeKey, 'key-c', $normalizer->getType(), 's:1337:"value-c";'),
                new PortalNodeStorageListResult($portalNodeKey, 'key-d', $normalizer->getType(), $normalizer->normalize('value-d')),
            ])
        ;

        $entries = \iterable_to_array($storage->list());
        static::assertSame([
            'key-a' => 'value-a',
            'key-b' => 'value-b',
            'key-d' => 'value-d',
        ], $entries);
    }
}
