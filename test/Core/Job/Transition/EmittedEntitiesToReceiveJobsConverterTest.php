<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Job\Transition;

use Heptacom\HeptaConnect\Core\Job\JobCollection;
use Heptacom\HeptaConnect\Core\Job\Transition\EmittedEntitiesToReceiveJobsConverter;
use Heptacom\HeptaConnect\Core\Job\Type\AbstractJobType;
use Heptacom\HeptaConnect\Core\Job\Type\Reception;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(JobCollection::class)]
#[CoversClass(EmittedEntitiesToReceiveJobsConverter::class)]
#[CoversClass(AbstractJobType::class)]
#[CoversClass(Reception::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(DatasetEntityCollection::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(TypedDatasetEntityCollection::class)]
#[CoversClass(MappingComponentStruct::class)]
#[CoversClass(PortalContract::class)]
#[CoversClass(PortalType::class)]
#[CoversClass(ReceptionRouteListCriteria::class)]
#[CoversClass(ReceptionRouteListResult::class)]
#[CoversClass(PreviewPortalNodeKey::class)]
#[CoversClass(AttachmentAwareTrait::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
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
