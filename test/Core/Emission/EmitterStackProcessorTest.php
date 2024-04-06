<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Emission\EmitterStack;
use Heptacom\HeptaConnect\Core\Emission\EmitterStackProcessor;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\ThrowEmitter;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Component\LogMessage
 * @covers \Heptacom\HeptaConnect\Core\Emission\EmitterStack
 * @covers \Heptacom\HeptaConnect\Core\Emission\EmitterStackProcessor
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\MappingCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\TypedMappingCollection
 */
final class EmitterStackProcessorTest extends TestCase
{
    public function testProcessingSucceeds(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        $emissionActor = new EmitterStackProcessor($logger);
        $result = $emissionActor->processStack(
            ['d89af996-6ee8-4ed8-81d3-ff41be138539'],
            new EmitterStack([
                new class() extends EmitterContract {
                    protected function supports(): string
                    {
                        return FooBarEntity::class;
                    }

                    protected function batch(iterable $externalIds, EmitContextInterface $context): iterable
                    {
                        foreach ($externalIds as $externalId) {
                            $entity = new FooBarEntity();
                            $entity->setPrimaryKey($externalId);

                            yield $entity;
                        }
                    }
                },
            ], FooBarEntity::class(), $logger),
            $this->createMock(EmitContextInterface::class)
        );
        static::assertCount(1, $result);
        static::assertSame('d89af996-6ee8-4ed8-81d3-ff41be138539', $result->first()->getPrimaryKey());
    }

    public function testProcessingFindsEntityWithoutPrimaryKey(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(static::once())
            ->method('critical')
            ->with(LogMessage::EMIT_NO_PRIMARY_KEY());

        $emissionActor = new EmitterStackProcessor($logger);
        $emissionActor->processStack(
            ['60448f35-302d-44b8-ad83-f94cc576ba06'],
            new EmitterStack([
                new class() extends EmitterContract {
                    protected function supports(): string
                    {
                        return FooBarEntity::class;
                    }

                    protected function batch(iterable $externalIds, EmitContextInterface $context): iterable
                    {
                        foreach ($externalIds as $_) {
                            yield new FooBarEntity();
                        }
                    }
                },
            ], FooBarEntity::class(), $logger),
            $this->createMock(EmitContextInterface::class)
        );
    }

    /**
     * @dataProvider provideEmitCount
     */
    public function testProcessingFails(int $count): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($count > 0 ? static::atLeastOnce() : static::never())
            ->method('critical')
            ->with(LogMessage::EMIT_NO_THROW());

        $emissionActor = new EmitterStackProcessor($logger);
        $emissionActor->processStack(
            \array_fill(0, $count, '13ec839a-31da-4f33-a46c-d372d9fb80da'),
            new EmitterStack(
                [new ThrowEmitter()],
                FooBarEntity::class(),
                $this->createMock(LoggerInterface::class)
            ),
            $this->createMock(EmitContextInterface::class)
        );
    }

    /**
     * @return iterable<array-key, array<array-key, int>>
     */
    public function provideEmitCount(): iterable
    {
        yield [0];
        yield [1];
        yield [2];
        yield [3];
        yield [4];
        yield [5];
        yield [6];
        yield [7];
        yield [8];
        yield [9];
        yield [10];
    }
}
