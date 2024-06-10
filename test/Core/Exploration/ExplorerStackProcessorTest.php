<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Exploration\ExplorerStack;
use Heptacom\HeptaConnect\Core\Exploration\ExplorerStackProcessor;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\ThrowExplorer;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(LogMessage::class)]
#[CoversClass(ExplorerStack::class)]
#[CoversClass(ExplorerStackProcessor::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(ExplorerContract::class)]
#[CoversClass(ExplorerCollection::class)]
#[CoversTrait(AttachmentAwareTrait::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
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
