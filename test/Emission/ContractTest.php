<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Emission;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 */
class ContractTest extends TestCase
{
    public function testExtendingEmitterContract(): void
    {
        $emitter = new class() extends EmitterContract {
            public function emit(MappingCollection $mappings, EmitContextInterface $context, EmitterStackInterface $stack): iterable
            {
                yield from [];
            }

            public function supports(): array
            {
                return [];
            }
        };
        static::assertEmpty($emitter->supports());
        static::assertCount(0, $emitter->emit(
            new MappingCollection(),
            $this->createMock(EmitContextInterface::class),
            $this->createMock(EmitterStackInterface::class)
        ));
    }

    public function testAttachingEmitterContract(): void
    {
        $emitter = new class() extends EmitterContract {
            protected function run(
                PortalContract $portal,
                MappingInterface $mapping,
                EmitContextInterface $context
            ): ?DatasetEntityContract {
                $good = new FirstEntity();
                $good->setPrimaryKey('good');

                return $good;
            }

            public function supports(): array
            {
                return [FirstEntity::class];
            }
        };
        $decoratingEmitter = new class() extends EmitterContract {
            public function emit(
                MappingCollection $mappings,
                EmitContextInterface $context,
                EmitterStackInterface $stack
            ): iterable {
                return $this->emitNextToExtend($stack, $mappings, $context);
            }

            protected function runToExtend(
                PortalContract $portal,
                MappingInterface $mapping,
                DatasetEntityContract $entity,
                EmitContextInterface $context
            ): ?DatasetEntityContract {
                $entity->attach(new class() implements AttachableInterface {});

                return $entity;
            }

            public function supports(): array
            {
                return [FirstEntity::class];
            }
        };
        static::assertContains(FirstEntity::class, $emitter->supports());
        static::assertContains(FirstEntity::class, $decoratingEmitter->supports());

        $context = $this->createMock(EmitContextInterface::class);
        $stack = $this->createMock(EmitterStackInterface::class);
        $mapping = $this->createMock(MappingInterface::class);
        $mappings = new MappingCollection([$mapping]);

        $mapping->method('getExternalId')->willReturn('');
        $stack->method('next')->willReturn($emitter->emit($mappings, $context, $stack));

        $emitted = new MappedDatasetEntityCollection($emitter->emit(
            $mappings,
            $context,
            $this->createMock(EmitterStackInterface::class)
        ));
        $decoratedEmitted = new MappedDatasetEntityCollection($decoratingEmitter->emit($mappings, $context, $stack));

        static::assertCount(1, $emitted);
        static::assertCount(0, $emitted->first()->getDatasetEntity()->getAttachments());
        static::assertCount(1, $decoratedEmitted);
        static::assertCount(1, $decoratedEmitted->first()->getDatasetEntity()->getAttachments());
    }
}
