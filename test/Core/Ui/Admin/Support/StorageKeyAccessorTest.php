<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Support;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\StorageKeyAccessor;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyNotSupportedException;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Support\StorageKeyAccessor
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException
 * @covers \Heptacom\HeptaConnect\Storage\Base\JobKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyNotSupportedException
 */
final class StorageKeyAccessorTest extends TestCase
{
    public function testPortalNodeExists(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $testKey = $this->createMock(PortalNodeKeyInterface::class);

        $portalNodeGetAction->expects(static::once())->method('get')->willReturn([
            new PortalNodeGetResult($testKey, FooBarPortal::class()),
        ]);
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::never())->method('get');
        $testKey->method('equals')->willReturnCallback(static fn ($key): bool => $key === $testKey);

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        static::assertTrue($accessor->exists($testKey));
    }

    public function testPortalNodeDoesntExists(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $testKey = $this->createMock(PortalNodeKeyInterface::class);

        $portalNodeGetAction->expects(static::once())->method('get')->willReturn([]);
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::never())->method('get');
        $testKey->method('equals')->willReturnCallback(static fn ($key): bool => $key === $testKey);

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        static::assertFalse($accessor->exists($testKey));
    }

    public function testPortalNodeKeyIsIncompatibleWithStorageErrorInStorage(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $testKey = $this->createMock(PortalNodeKeyInterface::class);

        $portalNodeGetAction->expects(static::once())->method('get')->willThrowException(new UnsupportedStorageKeyException(PortalNodeKeyInterface::class));
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::never())->method('get');

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        self::expectException(StorageKeyNotSupportedException::class);
        self::expectExceptionCode(1660417909);

        $accessor->exists($testKey);
    }

    public function testPortalNodeKeyRaisesErrorInStorage(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $testKey = $this->createMock(PortalNodeKeyInterface::class);

        $portalNodeGetAction->expects(static::once())->method('get')->willThrowException(new \RuntimeException('woops'));
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::never())->method('get');

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        self::expectException(ReadException::class);
        self::expectExceptionCode(1660417911);

        $accessor->exists($testKey);
    }

    public function testRouteExists(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $testKey = $this->createMock(RouteKeyInterface::class);

        $portalNodeGetAction->expects(static::never())->method('get');
        $routeGetAction->expects(static::once())->method('get')->willReturn([
            new RouteGetResult(
                $testKey,
                $this->createMock(PortalNodeKeyInterface::class),
                $this->createMock(PortalNodeKeyInterface::class),
                FooBarEntity::class(),
                []
            ),
        ]);
        $jobGetAction->expects(static::never())->method('get');
        $testKey->method('equals')->willReturnCallback(static fn ($key): bool => $key === $testKey);

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        static::assertTrue($accessor->exists($testKey));
    }

    public function testRouteDoesntExists(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $testKey = $this->createMock(RouteKeyInterface::class);

        $portalNodeGetAction->expects(static::never())->method('get');
        $routeGetAction->expects(static::once())->method('get')->willReturn([]);
        $jobGetAction->expects(static::never())->method('get');
        $testKey->method('equals')->willReturnCallback(static fn ($key): bool => $key === $testKey);

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        static::assertFalse($accessor->exists($testKey));
    }

    public function testRouteKeyIsIncompatibleWithStorageErrorInStorage(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $testKey = $this->createMock(RouteKeyInterface::class);

        $portalNodeGetAction->expects(static::never())->method('get');
        $routeGetAction->expects(static::once())->method('get')->willThrowException(new UnsupportedStorageKeyException(RouteKeyInterface::class));
        $jobGetAction->expects(static::never())->method('get');

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        self::expectException(StorageKeyNotSupportedException::class);
        self::expectExceptionCode(1660417909);

        $accessor->exists($testKey);
    }

    public function testJobExists(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $testKey = $this->createMock(JobKeyInterface::class);

        $portalNodeGetAction->expects(static::never())->method('get');
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::once())->method('get')->willReturn([
            new JobGetResult(
                '',
                $testKey,
                new MappingComponentStruct($this->createMock(PortalNodeKeyInterface::class), FooBarEntity::class(), ''),
                []
            ),
        ]);
        $testKey->method('equals')->willReturnCallback(static fn ($key): bool => $key === $testKey);

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        static::assertTrue($accessor->exists($testKey));
    }

    public function testJobDoesntExists(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $testKey = $this->createMock(JobKeyInterface::class);

        $portalNodeGetAction->expects(static::never())->method('get');
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::once())->method('get')->willReturn([]);
        $testKey->method('equals')->willReturnCallback(static fn ($key): bool => $key === $testKey);

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        static::assertFalse($accessor->exists($testKey));
    }

    public function testRouteIsIncompatibleWithStorageErrorInStorage(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $testKey = $this->createMock(JobKeyInterface::class);

        $portalNodeGetAction->expects(static::never())->method('get');
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::once())->method('get')->willThrowException(new UnsupportedStorageKeyException(JobKeyInterface::class));

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        self::expectException(StorageKeyNotSupportedException::class);
        self::expectExceptionCode(1660417909);

        $accessor->exists($testKey);
    }

    /**
     * @dataProvider provideSerializationStorageKeys
     */
    public function testKeySerialize(StorageKeyInterface $key): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);

        $storageKeyGenerator->expects(static::once())->method('serialize')->with(new IsIdentical($key))->willReturn('foobar');
        $portalNodeGetAction->expects(static::never())->method('get');
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::never())->method('get');

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        static::assertSame('foobar', $accessor->serialize($key));
    }

    /**
     * @dataProvider provideSerializationStorageKeys
     */
    public function testKeySerializeFailsInImplementation(StorageKeyInterface $key): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);

        $storageKeyGenerator->expects(static::once())->method('serialize')->willThrowException(new \RuntimeException('Woopsie'));
        $portalNodeGetAction->expects(static::never())->method('get');
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::never())->method('get');

        self::expectException(ReadException::class);
        self::expectExceptionCode(1660417912);

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        $accessor->serialize($key);
    }

    /**
     * @dataProvider provideSerializationStorageKeys
     */
    public function testKeyDeserialize(StorageKeyInterface $key): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);

        $storageKeyGenerator->expects(static::once())->method('deserialize')->with(new IsIdentical('foobar'))->willReturn($key);
        $portalNodeGetAction->expects(static::never())->method('get');
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::never())->method('get');

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        static::assertSame($key, $accessor->deserialize('foobar'));
    }

    public function testKeyDeserializeFailsInImplementation(): void
    {
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);

        $storageKeyGenerator->expects(static::once())->method('deserialize')->willThrowException(new \RuntimeException('Woopsie'));
        $portalNodeGetAction->expects(static::never())->method('get');
        $routeGetAction->expects(static::never())->method('get');
        $jobGetAction->expects(static::never())->method('get');

        $accessor = new StorageKeyAccessor($storageKeyGenerator, $portalNodeGetAction, $routeGetAction, $jobGetAction);

        self::expectException(ReadException::class);
        self::expectExceptionCode(1660417913);

        $accessor->deserialize('foobar');
    }

    public function provideSerializationStorageKeys(): iterable
    {
        yield [$this->createMock(PortalNodeKeyInterface::class)];
        yield [$this->createMock(RouteKeyInterface::class)];
        yield [$this->createMock(JobKeyInterface::class)];
    }
}
