<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Emission\AbstractBufferedResultProcessingEmitter;
use Heptacom\HeptaConnect\Core\Emission\EmissionFlowEmittersFactory;
use Heptacom\HeptaConnect\Core\Emission\ReceiveJobDispatchingEmitter;
use Heptacom\HeptaConnect\Core\Job\Contract\JobDispatcherContract;
use Heptacom\HeptaConnect\Core\Job\Transition\Contract\EmittedEntitiesToJobsConverterInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractBufferedResultProcessingEmitter::class)]
#[CoversClass(EmissionFlowEmittersFactory::class)]
#[CoversClass(ReceiveJobDispatchingEmitter::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(TypedDatasetEntityCollection::class)]
#[CoversClass(EmitterContract::class)]
#[CoversClass(EmitterCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
final class EmissionFlowEmittersFactoryTest extends TestCase
{
    public function testCollectionContainsExpectedServices(): void
    {
        $factory = new EmissionFlowEmittersFactory(
            $this->createMock(EmittedEntitiesToJobsConverterInterface::class),
            $this->createMock(JobDispatcherContract::class),
            1
        );
        $emitters = $factory->createEmitters(
            $this->createMock(PortalNodeKeyInterface::class),
            FooBarEntity::class()
        );

        static::assertCount(1, $emitters);
        static::assertInstanceOf(ReceiveJobDispatchingEmitter::class, $emitters[0]);
    }
}
