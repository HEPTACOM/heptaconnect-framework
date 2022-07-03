<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Exploration;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerStack;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\EntityTypeClassString
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerStack
 */
final class ContractTest extends TestCase
{
    public function testExtendingExplorerContractLikeIn0Dot9(): void
    {
        $explorer = new class() extends ExplorerContract {
            public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
            {
                yield from [];
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertTrue(FirstEntity::class()->same($explorer->getSupportedEntityType()));
        static::assertCount(0, $explorer->explore(
            $this->createMock(ExploreContextInterface::class),
            $this->createMock(ExplorerStackInterface::class)
        ));
    }

    public function testExtendingExplorerContract(): void
    {
        $explorer = new class() extends ExplorerContract {
            public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
            {
                yield from [];
            }

            protected function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertTrue(FirstEntity::class()->same($explorer->getSupportedEntityType()));
        static::assertCount(0, $explorer->explore(
            $this->createMock(ExploreContextInterface::class),
            $this->createMock(ExplorerStackInterface::class)
        ));
    }

    public function testSkippingExplorerContract(): void
    {
        $explorer = new class() extends ExplorerContract {
            protected function run(ExploreContextInterface $context): iterable
            {
                yield 'good';
                yield 'bad';
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        $decoratingExplorer = new class() extends ExplorerContract {
            public function supports(): string
            {
                return FirstEntity::class;
            }

            protected function isAllowed(string $externalId, ?DatasetEntityContract $entity, ExploreContextInterface $context): bool
            {
                return $externalId === 'good';
            }
        };
        static::assertTrue(FirstEntity::class()->same($explorer->getSupportedEntityType()));
        static::assertTrue(FirstEntity::class()->same($decoratingExplorer->getSupportedEntityType()));

        $context = $this->createMock(ExploreContextInterface::class);

        static::assertCount(1, (new ExplorerStack([$decoratingExplorer, $explorer]))->next($context));
        static::assertCount(2, (new ExplorerStack([$explorer]))->next($context));
    }
}
