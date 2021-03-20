<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Emission;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingCollection;
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

            public function supports(): string
            {
                return DatasetEntityContract::class;
            }
        };
        static::assertSame(DatasetEntityContract::class, $emitter->supports());
        static::assertCount(0, $emitter->emit(
            new MappingCollection(),
            $this->createMock(EmitContextInterface::class),
            $this->createMock(EmitterStackInterface::class)
        ));
    }

    public function testAttachingEmitterContract(): void
    {
        $emitter = new class() extends EmitterContract {
            protected function run(MappingInterface $mapping, EmitContextInterface $context): ?DatasetEntityContract
            {
                $good = new FirstEntity();
                $good->setPrimaryKey('good');

                return $good;
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        $decoratingEmitter = new class() extends EmitterContract {
            protected function extend(
                MappingInterface $mapping,
                DatasetEntityContract $entity,
                EmitContextInterface $context
            ): ?DatasetEntityContract {
                $entity->attach(new class() implements AttachableInterface {});

                return $entity;
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertSame(FirstEntity::class, $emitter->supports());
        static::assertSame(FirstEntity::class, $decoratingEmitter->supports());

        $context = $this->createMock(EmitContextInterface::class);
        $mapping = $this->createMock(MappingInterface::class);
        $mappings = new MappingCollection([$mapping]);

        $mapping->method('getExternalId')->willReturn('');

        $emitted = new MappedDatasetEntityCollection((new EmitterStack([$emitter]))->next($mappings, $context));
        $decoratedEmitted = new MappedDatasetEntityCollection(
            (new EmitterStack([$decoratingEmitter, $emitter]))
                ->next($mappings, $context)
        );

        static::assertCount(1, $emitted);
        static::assertCount(0, $emitted->first()->getDatasetEntity()->getAttachments());
        static::assertCount(1, $decoratedEmitted);
        static::assertCount(1, $decoratedEmitted->first()->getDatasetEntity()->getAttachments());
    }
}
