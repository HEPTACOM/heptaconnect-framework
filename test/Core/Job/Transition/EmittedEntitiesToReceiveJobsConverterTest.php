<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Job\Transition;

use Heptacom\HeptaConnect\Core\Job\Transition\EmittedEntitiesToReceiveJobsConverter;
use Heptacom\HeptaConnect\Core\Job\Type\Reception;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Job\JobCollection
 * @covers \Heptacom\HeptaConnect\Core\Job\Transition\EmittedEntitiesToReceiveJobsConverter
 * @covers \Heptacom\HeptaConnect\Core\Job\Type\AbstractJobType
 * @covers \Heptacom\HeptaConnect\Core\Job\Type\Reception
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 */
final class EmittedEntitiesToReceiveJobsConverterTest extends TestCase
{
    public function testMultiplyEmissionStackResultByRoutes(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $routeList = $this->createMock(ReceptionRouteListActionInterface::class);

        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $routeKeyA = $this->createMock(RouteKeyInterface::class);
        $routeKeyB = $this->createMock(RouteKeyInterface::class);
        $entityA = new FooBarEntity();
        $entityA->setPrimaryKey('entityA');
        $entityB = new FooBarEntity();
        $entityB->setPrimaryKey('entityB');

        $routeList->method('list')->willReturn([
            new ReceptionRouteListResult($routeKeyA),
            new ReceptionRouteListResult($routeKeyB),
        ]);
        $routeKeyA->method('equals')->willReturnCallback(static fn (RouteKeyInterface $key): bool => $key === $routeKeyA);
        $routeKeyB->method('equals')->willReturnCallback(static fn (RouteKeyInterface $key): bool => $key === $routeKeyB);
        $logger->expects(static::never())->method('warning');

        $converter = new EmittedEntitiesToReceiveJobsConverter($routeList, $logger);

        $result = $converter->convert($portalNodeKey, new DatasetEntityCollection([$entityB, $entityA]));

        $scenarios = [];

        foreach ($result as $job) {
            static::assertInstanceOf(Reception::class, $job);
            $scenario = '';

            static::assertTrue($portalNodeKey->equals($job->getMappingComponent()->getPortalNodeKey()));

            if ($job->getRouteKey()->equals($routeKeyA)) {
                $scenario .= 'routeA';
            }

            if ($job->getRouteKey()->equals($routeKeyB)) {
                $scenario .= 'routeB';
            }

            $scenario .= $job->getMappingComponent()->getEntityType();
            $scenario .= $job->getMappingComponent()->getExternalId();

            $scenarios[] = $scenario;
        }

        \sort($scenarios);

        static::assertSame([
            'routeA' . FooBarEntity::class . 'entityA',
            'routeA' . FooBarEntity::class . 'entityB',
            'routeB' . FooBarEntity::class . 'entityA',
            'routeB' . FooBarEntity::class . 'entityB',
        ], $scenarios);
    }

    public function testEmptyResultOnEmptyRoutes(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $routeList = $this->createMock(ReceptionRouteListActionInterface::class);

        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $routeKeyA = $this->createMock(RouteKeyInterface::class);
        $routeKeyB = $this->createMock(RouteKeyInterface::class);
        $entityA = new FooBarEntity();
        $entityA->setPrimaryKey('entityA');
        $entityB = new FooBarEntity();
        $entityB->setPrimaryKey('entityB');

        $routeList->method('list')->willReturn([]);
        $routeKeyA->method('equals')->willReturnCallback(static fn (RouteKeyInterface $key): bool => $key === $routeKeyA);
        $routeKeyB->method('equals')->willReturnCallback(static fn (RouteKeyInterface $key): bool => $key === $routeKeyB);
        $logger->expects(static::once())->method('warning');

        $converter = new EmittedEntitiesToReceiveJobsConverter($routeList, $logger);

        $result = $converter->convert($portalNodeKey, new DatasetEntityCollection([$entityA, $entityB]));

        static::assertCount(0, $result);
    }

    public function testEmptyResultOnEmptyEmissionStackResult(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $routeList = $this->createMock(ReceptionRouteListActionInterface::class);

        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $routeKeyA = $this->createMock(RouteKeyInterface::class);
        $routeKeyB = $this->createMock(RouteKeyInterface::class);

        $routeList->method('list')->willReturn([
            new ReceptionRouteListResult($routeKeyA),
            new ReceptionRouteListResult($routeKeyB),
        ]);
        $routeKeyA->method('equals')->willReturnCallback(static fn (RouteKeyInterface $key): bool => $key === $routeKeyA);
        $routeKeyB->method('equals')->willReturnCallback(static fn (RouteKeyInterface $key): bool => $key === $routeKeyB);
        $logger->expects(static::once())->method('warning');

        $converter = new EmittedEntitiesToReceiveJobsConverter($routeList, $logger);

        $result = $converter->convert($portalNodeKey, new DatasetEntityCollection());

        static::assertCount(0, $result);
    }

    public function testEmptyResultOnEmptyEmissionStackResultAndEmptyRoutes(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $routeList = $this->createMock(ReceptionRouteListActionInterface::class);

        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $routeList->method('list')->willReturn([]);
        $logger->expects(static::once())->method('warning');

        $converter = new EmittedEntitiesToReceiveJobsConverter($routeList, $logger);

        $result = $converter->convert($portalNodeKey, new DatasetEntityCollection());

        static::assertCount(0, $result);
    }
}
