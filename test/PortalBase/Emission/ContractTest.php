<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Emission;

use Heptacom\HeptaConnect\Core\Emission\EmitterStack;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachableInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Utility\Attachment\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Core\Emission\EmitterStack
 */
final class ContractTest extends TestCase
{
    public function testExtendingEmitterContract(): void
    {
        $emitter = new class() extends EmitterContract {
            public function emit(iterable $externalIds, EmitContextInterface $context, EmitterStackInterface $stack): iterable
            {
                yield from [];
            }

            protected function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertTrue(FirstEntity::class()->equals($emitter->getSupportedEntityType()));
        $emitResult = \iterable_to_array($emitter->emit(
            [],
            $this->createMock(EmitContextInterface::class),
            $this->createMock(EmitterStackInterface::class)
        ));
        static::assertCount(0, $emitResult);
    }

    public function testExtendingEmitterContractLikeIn0Dot9(): void
    {
        $emitter = new class() extends EmitterContract {
            public function emit(iterable $externalIds, EmitContextInterface $context, EmitterStackInterface $stack): iterable
            {
                yield from [];
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertTrue(FirstEntity::class()->equals($emitter->getSupportedEntityType()));
        $emitResult = \iterable_to_array($emitter->emit(
            [],
            $this->createMock(EmitContextInterface::class),
            $this->createMock(EmitterStackInterface::class)
        ));
        static::assertCount(0, $emitResult);
    }

    public function testAttachingEmitterContract(): void
    {
        $emitter = new class() extends EmitterContract {
            protected function run(string $externalId, EmitContextInterface $context): ?DatasetEntityContract
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
            protected function extend(DatasetEntityContract $entity, EmitContextInterface $context): DatasetEntityContract
            {
                $entity->attach(new class() implements AttachableInterface {
                });

                return $entity;
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertTrue(FirstEntity::class()->equals($emitter->getSupportedEntityType()));
        static::assertTrue(FirstEntity::class()->equals($decoratingEmitter->getSupportedEntityType()));

        $context = $this->createMock(EmitContextInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $externalIds = ['good'];

        $container->method('get')->willReturn($logger);

        $emitted = new DatasetEntityCollection(
            (new EmitterStack(
                [$emitter],
                $emitter->getSupportedEntityType(),
                $this->createMock(LoggerInterface::class)
            ))->next($externalIds, $context)
        );
        $decoratedEmitted = new DatasetEntityCollection(
            (new EmitterStack(
                [$decoratingEmitter, $emitter],
                $emitter->getSupportedEntityType(),
                $this->createMock(LoggerInterface::class)
            ))
                ->next($externalIds, $context)
        );

        static::assertCount(1, $emitted);
        static::assertCount(0, $emitted->first()->getAttachments());
        static::assertCount(1, $decoratedEmitted);
        static::assertCount(1, $decoratedEmitted->first()->getAttachments());
    }

    public function testRunMethodExtensionWhenImplemented(): void
    {
        $emitter = new class() extends EmitterContract {
            protected function run(string $externalId, EmitContextInterface $context): ?DatasetEntityContract
            {
                return null;
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(static::once())->method('error');

        $context = $this->createMock(EmitContextInterface::class);
        $context->method('getLogger')->willReturn($logger);

        $externalIds = ['foo'];

        \iterable_to_array(
            (new EmitterStack(
                [$emitter],
                $emitter->getSupportedEntityType(),
                $this->createMock(LoggerInterface::class)
            ))
                ->next($externalIds, $context)
        );
    }

    public function testRunMethodExtensionWhenNotImplemented(): void
    {
        $emitter = new class() extends EmitterContract {
            public function supports(): string
            {
                return FirstEntity::class;
            }
        };

        $context = $this->createMock(EmitContextInterface::class);
        $context->expects(static::never())->method('markAsFailed');
        $externalIds = ['foo'];

        \iterable_to_array(
            (new EmitterStack(
                [$emitter],
                $emitter->getSupportedEntityType(),
                $this->createMock(LoggerInterface::class)
            ))
                ->next($externalIds, $context)
        );
    }
}
