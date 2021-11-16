<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Emission;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack
 */
class ContractTest extends TestCase
{
    public function testExtendingEmitterContract(): void
    {
        $emitter = new class() extends EmitterContract {
            public function emit(iterable $externalIds, EmitContextInterface $context, EmitterStackInterface $stack): iterable
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
            [],
            $this->createMock(EmitContextInterface::class),
            $this->createMock(EmitterStackInterface::class)
        ));
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
        $container = $this->createMock(ContainerInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $externalIds = ['good'];

        $container->method('get')->willReturn($logger);

        $emitted = new DatasetEntityCollection((new EmitterStack([$emitter], $emitter->supports()))->next($externalIds, $context));
        $decoratedEmitted = new DatasetEntityCollection(
            (new EmitterStack([$decoratingEmitter, $emitter], $emitter->supports()))
                ->next($externalIds, $context)
        );

        static::assertCount(1, $emitted);
        static::assertCount(0, $emitted->first()->getAttachments());
        static::assertCount(1, $decoratedEmitted);
        static::assertCount(1, $decoratedEmitted->first()->getAttachments());
    }

    public function testRunMethodExtensionWhenImplemented()
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

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->willReturnCallback(function (string $id) use ($logger) {
            switch ($id) {
                case LoggerInterface::class:
                    return $logger;
            }

            return null;
        });

        $context = $this->createMock(EmitContextInterface::class);
        $context->method('getContainer')->willReturn($container);

        $externalIds = ['foo'];

        \iterable_to_array((new EmitterStack([$emitter], $emitter->supports()))->next($externalIds, $context));
    }

    public function testRunMethodExtensionWhenNotImplemented()
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

        \iterable_to_array((new EmitterStack([$emitter], $emitter->supports()))->next($externalIds, $context));
    }
}
