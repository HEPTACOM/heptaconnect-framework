<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Emission\EmitterStack;
use Heptacom\HeptaConnect\Core\Emission\EmitterStackProcessor;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\ThrowEmitter;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Storage\Base\MappingCollection;
use Heptacom\HeptaConnect\Storage\Base\TypedMappingCollection;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(LogMessage::class)]
#[CoversClass(EmitterStack::class)]
#[CoversClass(EmitterStackProcessor::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(DatasetEntityCollection::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(TypedDatasetEntityCollection::class)]
#[CoversClass(EmitterContract::class)]
#[CoversClass(EmitterCollection::class)]
#[CoversClass(MappingCollection::class)]
#[CoversClass(TypedMappingCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
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

    #[DataProvider('provideEmitCount')]
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
    public static function provideEmitCount(): iterable
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
