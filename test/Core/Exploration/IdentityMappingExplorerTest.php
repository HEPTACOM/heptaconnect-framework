<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Exploration\AbstractBufferedResultProcessingExplorer;
use Heptacom\HeptaConnect\Core\Exploration\IdentityMappingExplorer;
use Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(AbstractBufferedResultProcessingExplorer::class)]
#[CoversClass(IdentityMappingExplorer::class)]
#[CoversClass(PrimaryKeyToEntityHydrator::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(DatasetEntityCollection::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(TypedDatasetEntityCollection::class)]
#[CoversClass(ExplorerContract::class)]
#[CoversClass(IdentityMapPayload::class)]
#[CoversClass(IdentityMapResult::class)]
#[CoversClass(AttachmentAwareTrait::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
#[CoversClass(StringCollection::class)]
final class IdentityMappingExplorerTest extends TestCase
{
    public function testMapsPksInBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize * 5);

        $identityMapAction
            ->expects(static::exactly(5))
            ->method('map')
            ->willReturnCallback(static::validateMapPayloadCountAndPks(\array_fill(0, 5, $batchSize), $pks));

        $explorer = new IdentityMappingExplorer($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls($pks, []);

        \iterable_to_array($explorer->explore($context, $stack));

        static::assertSame([], $pks);
    }

    public function testConvertsRemainingPksIfPkCountIsNotDividablePerfectlyByBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize * 5 + 2);

        $context->method('getLogger')->willReturn($this->createMock(LoggerInterface::class));
        $identityMapAction
            ->expects(static::exactly(6))
            ->method('map')
            ->willReturnCallback(static::validateMapPayloadCountAndPks([$batchSize, $batchSize, $batchSize, $batchSize, $batchSize, 2], $pks));

        $explorer = new IdentityMappingExplorer($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls($pks, []);

        \iterable_to_array($explorer->explore($context, $stack));

        static::assertSame([], $pks);
    }

    public function testConvertsPksToJobsAndDispatchesThemUntilAnExceptionIsThrown(): void
    {
        $entityType = FooBarEntity::class();
        $portalNodeLogger = $this->createMock(LoggerInterface::class);
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize + 2);

        $context->method('getLogger')->willReturn($portalNodeLogger);
        $identityMapAction
            ->expects(static::exactly(2))
            ->method('map')
            ->willReturnCallback(static::validateMapPayloadCountAndPks([$batchSize, 2], $pks));
        $portalNodeLogger->expects(static::once())->method('critical');

        $explorer = new IdentityMappingExplorer($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnCallback(static function () use ($pks): iterable {
            yield from $pks;

            throw new \RuntimeException('Test message');
        });

        \iterable_to_array($explorer->explore($context, $stack));

        static::assertSame([], $pks);
    }

    private function generatePrimaryKeys(int $count): array
    {
        return \array_map('strval', \range(100, 100 + ($count - 1)));
    }

    /**
     * @param int[]    $batchSizes
     * @param string[] $primaryKeys
     */
    private function validateMapPayloadCountAndPks(array $batchSizes, array &$primaryKeys): \Closure
    {
        return static function (IdentityMapPayload $payload) use (&$batchSizes, &$primaryKeys): IdentityMapResult {
            $batchSize = \array_shift($batchSizes);

            static::assertIsInt($batchSize);
            static::assertCount($batchSize, $payload->getEntityCollection());

            foreach ($payload->getEntityCollection() as $entity) {
                $pk = $entity->getPrimaryKey();

                static::assertNotNull($pk);
                static::assertContains($pk, $primaryKeys);

                $primaryKeys = \array_diff($primaryKeys, [$pk]);
            }

            return new IdentityMapResult(new MappedDatasetEntityCollection());
        };
    }
}
