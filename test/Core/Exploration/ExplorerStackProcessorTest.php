<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Exploration\ExplorerStack;
use Heptacom\HeptaConnect\Core\Exploration\ExplorerStackProcessor;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\ThrowExplorer;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Component\LogMessage
 * @covers \Heptacom\HeptaConnect\Core\Exploration\ExplorerStack
 * @covers \Heptacom\HeptaConnect\Core\Exploration\ExplorerStackProcessor
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 */
final class ExplorerStackProcessorTest extends TestCase
{
    public function testProcessingSucceeds(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        $stackProcessor = new ExplorerStackProcessor($logger);
        $result = \iterable_to_array($stackProcessor->processStack(
            new ExplorerStack([
                new class() extends ExplorerContract {
                    protected function supports(): string
                    {
                        return FooBarEntity::class;
                    }

                    protected function run(ExploreContextInterface $context): iterable
                    {
                        $entity = new FooBarEntity();
                        $entity->setPrimaryKey('d89af996-6ee8-4ed8-81d3-ff41be138539');

                        yield $entity;
                    }
                },
            ], FooBarEntity::class(), $logger),
            $this->createMock(ExploreContextInterface::class)
        ));
        static::assertCount(1, $result);
        static::assertInstanceOf(DatasetEntityContract::class, \current($result));
        static::assertSame('d89af996-6ee8-4ed8-81d3-ff41be138539', \current($result)->getPrimaryKey());
    }

    public function testProcessingFails(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(static::once())
            ->method('critical')
            ->with(LogMessage::EXPLORE_NO_THROW());

        $stackProcessor = new ExplorerStackProcessor($logger);
        \iterable_to_array($stackProcessor->processStack(
            new ExplorerStack(
                [new ThrowExplorer()],
                FooBarEntity::class(),
                $this->createMock(LoggerInterface::class)
            ),
            $this->createMock(ExploreContextInterface::class)
        ));
    }
}
