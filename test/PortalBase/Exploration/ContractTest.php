<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Exploration;

use Heptacom\HeptaConnect\Core\Exploration\ExplorerStack;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Exploration\ExplorerStack
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
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
        static::assertTrue(FirstEntity::class()->equals($explorer->getSupportedEntityType()));
        $exploreResult = \iterable_to_array($explorer->explore(
            $this->createMock(ExploreContextInterface::class),
            $this->createMock(ExplorerStackInterface::class)
        ));
        static::assertCount(0, $exploreResult);
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
        static::assertTrue(FirstEntity::class()->equals($explorer->getSupportedEntityType()));
        $exploreResult = \iterable_to_array($explorer->explore(
            $this->createMock(ExploreContextInterface::class),
            $this->createMock(ExplorerStackInterface::class)
        ));
        static::assertCount(0, $exploreResult);
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
        static::assertTrue(FirstEntity::class()->equals($explorer->getSupportedEntityType()));
        static::assertTrue(FirstEntity::class()->equals($decoratingExplorer->getSupportedEntityType()));

        $context = $this->createMock(ExploreContextInterface::class);

        $exploreResult = \iterable_to_array((new ExplorerStack(
            [$decoratingExplorer, $explorer],
            FirstEntity::class(),
            $this->createMock(LoggerInterface::class)
        ))->next($context));
        static::assertCount(1, $exploreResult);
        $exploreResult = \iterable_to_array((new ExplorerStack(
            [$explorer],
            FirstEntity::class(),
            $this->createMock(LoggerInterface::class)
        ))->next($context));
        static::assertCount(2, $exploreResult);
    }
}
