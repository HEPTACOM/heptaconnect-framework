<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Emission\Contract\EmissionFlowEmittersFactoryInterface;
use Heptacom\HeptaConnect\Core\Emission\IdentityMappingEmitter;
use Heptacom\HeptaConnect\Core\Exploration\DirectEmissionFlowEmittersFactory;
use Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEmitter;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Emission\AbstractBufferedResultProcessingEmitter
 * @covers \Heptacom\HeptaConnect\Core\Emission\IdentityMappingEmitter
 * @covers \Heptacom\HeptaConnect\Core\Exploration\DirectEmissionFlowEmittersFactory
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 */
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
