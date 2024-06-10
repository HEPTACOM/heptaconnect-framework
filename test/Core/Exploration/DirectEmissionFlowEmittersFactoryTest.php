<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Emission\AbstractBufferedResultProcessingEmitter;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmissionFlowEmittersFactoryInterface;
use Heptacom\HeptaConnect\Core\Emission\IdentityMappingEmitter;
use Heptacom\HeptaConnect\Core\Exploration\DirectEmissionFlowEmittersFactory;
use Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEmitter;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractBufferedResultProcessingEmitter::class)]
#[CoversClass(IdentityMappingEmitter::class)]
#[CoversClass(DirectEmissionFlowEmittersFactory::class)]
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
final class DirectEmissionFlowEmittersFactoryTest extends TestCase
{
    public function testCollectionContainsExpectedServices(): void
    {
        $emissionEmittersFactory = $this->createMock(EmissionFlowEmittersFactoryInterface::class);
        $factory = new DirectEmissionFlowEmittersFactory(
            $emissionEmittersFactory,
            new PrimaryKeyToEntityHydrator(),
            $this->createMock(IdentityMapActionInterface::class),
            1
        );

        $emissionEmittersFactory->method('createEmitters')->willReturn(new EmitterCollection([
            new FooBarEmitter(1),
        ]));

        $emitters = $factory->createEmitters(
            $this->createMock(PortalNodeKeyInterface::class),
            FooBarEntity::class()
        );

        static::assertCount(2, $emitters);
        static::assertInstanceOf(IdentityMappingEmitter::class, $emitters[0]);
        static::assertInstanceOf(FooBarEmitter::class, $emitters[1]);
    }
}
