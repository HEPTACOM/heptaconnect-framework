<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStorageGetUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\Contract\PortalNodeExistenceSeparatorInterface;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparationResult;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet\PortalNodeStorageGetCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet\PortalNodeStorageGetResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidPortalNodeStorageValueException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStorageGetUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparationResult
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet\PortalNodeStorageGetCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet\PortalNodeStorageGetResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidPortalNodeStorageValueException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException
 */
final class PortalNodeStorageGetUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testReadingValues(): void
    {
        $portalNodeExistenceSeparator = $this->createMock(PortalNodeExistenceSeparatorInterface::class);
        $storageFactory = $this->createMock(PortalStorageFactory::class);
        $action = new PortalNodeStorageGetUi(
            $this->createAuditTrailFactory(),
            $portalNodeExistenceSeparator,
            $storageFactory
        );
        $portalNodeKey = $this->createPortalNodeKey();

        $portalNodeExistenceSeparator->method('separateKeys')->willReturn(new PortalNodeExistenceSeparationResult(
            new PortalNodeKeyCollection(),
            new PortalNodeKeyCollection([$portalNodeKey]),
            new PortalNodeKeyCollection(),
        ));

        $storage = $this->createCache();
        $storageFactory->method('createPortalStorage')->willReturn($storage);

        $storage->setMultiple([
            'string' => 'string value',
            'int' => 1337,
            'null' => null,
            'bool' => true,
            'float' => 3.14,
            'object' => new \DateTimeImmutable(),
        ]);

        $criteria = new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection());
        $storageResult = \iterable_to_array($action->get($criteria, $this->createUiActionContext()));
        static::assertCount(0, $storageResult);

        $criteria = new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection([
            'string',
            'unknown',
            'int',
            'bool',
            'float',
            'null',
        ]));
        $storageResult = \array_merge(...\iterable_to_array(\iterable_map(
            $action->get($criteria, $this->createUiActionContext()),
            static fn (PortalNodeStorageGetResult $result): array => [
                $result->getStorageKey() => $result->getValue(),
            ]
        )));

        static::assertCount(6, $storageResult);
        static::assertArrayHasKey('string', $storageResult);
        static::assertIsString($storageResult['string']);
        static::assertArrayHasKey('unknown', $storageResult);
        static::assertNull($storageResult['unknown']);
        static::assertArrayHasKey('int', $storageResult);
        static::assertIsInt($storageResult['int']);
        static::assertArrayHasKey('bool', $storageResult);
        static::assertIsBool($storageResult['bool']);
        static::assertArrayHasKey('float', $storageResult);
        static::assertIsFloat($storageResult['float']);
        static::assertArrayHasKey('null', $storageResult);
        static::assertNull($storageResult['null']);

        static::expectException(InvalidPortalNodeStorageValueException::class);
        static::expectExceptionCode(1673129102);
        $criteria->getStorageKeys()->push(['object']);
        \iterable_to_array($action->get($criteria, $this->createUiActionContext()));
    }

    public function testMissingPortalNode(): void
    {
        $portalNodeExistenceSeparator = $this->createMock(PortalNodeExistenceSeparatorInterface::class);
        $storageFactory = $this->createMock(PortalStorageFactory::class);
        $action = new PortalNodeStorageGetUi(
            $this->createAuditTrailFactory(),
            $portalNodeExistenceSeparator,
            $storageFactory
        );
        $portalNodeKey = $this->createPortalNodeKey();

        $portalNodeExistenceSeparator->method('separateKeys')->willReturn(new PortalNodeExistenceSeparationResult(
            new PortalNodeKeyCollection(),
            new PortalNodeKeyCollection(),
            new PortalNodeKeyCollection([$portalNodeKey]),
        ));

        $storage = $this->createCache();
        $storageFactory->method('createPortalStorage')->willReturn($storage);

        $criteria = new PortalNodeStorageGetCriteria($portalNodeKey, new StringCollection());

        static::expectException(PortalNodesMissingException::class);

        \iterable_to_array($action->get($criteria, $this->createUiActionContext()));
    }

    private function createCache(): PortalStorageInterface
    {
        return new class() extends Psr16Cache implements PortalStorageInterface {
            private ArrayAdapter $adapter;

            public function __construct()
            {
                $this->adapter = new ArrayAdapter();
                parent::__construct($this->adapter);
            }

            public function list(): iterable
            {
                return $this->getMultiple(\array_keys($this->adapter->getValues()));
            }
        };
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
