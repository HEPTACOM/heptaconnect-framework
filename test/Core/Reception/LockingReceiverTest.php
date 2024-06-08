<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Reception;

use Heptacom\HeptaConnect\Core\Reception\LockingReceiver;
use Heptacom\HeptaConnect\Core\Reception\Support\LockAttachable;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentCollection;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Lock\LockInterface;

#[CoversClass(LockingReceiver::class)]
#[CoversClass(LockAttachable::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(DatasetEntityCollection::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(TypedDatasetEntityCollection::class)]
#[CoversClass(ReceiverContract::class)]
#[CoversTrait(AttachmentAwareTrait::class)]
#[CoversClass(AttachmentCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
final class LockingReceiverTest extends TestCase
{
    public function testNoRetryAndAllSucceedsAndAllHappensAtOnceWithoutLocks(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $receiver = new LockingReceiver(FooBarEntity::class(), $logger);
        $stack = $this->createMock(ReceiverStackInterface::class);
        $context = $this->createMock(ReceiveContextInterface::class);
        $entity = new FooBarEntity();
        $entity->setPrimaryKey('abc');

        $logger->expects(static::never())->method('alert');
        $logger->expects(static::never())->method('critical');
        $logger->expects(static::never())->method('emergency');
        $logger->expects(static::never())->method('error');
        $logger->expects(static::never())->method('warning');
        $stack->method('next')->willReturnArgument(0);

        $entities = new TypedDatasetEntityCollection($receiver->getSupportedEntityType());
        $entities->push(\array_fill(0, 20, $entity));

        $result = $receiver->receive($entities, $context, $stack);

        static::assertCount(20, $result);
    }

    public function testNoRetryAndAllSucceedsAndAllHappensAtOnceWithLocks(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $receiver = new LockingReceiver(FooBarEntity::class(), $logger);
        $stack = $this->createMock(ReceiverStackInterface::class);
        $context = $this->createMock(ReceiveContextInterface::class);

        $logger->expects(static::never())->method('alert');
        $logger->expects(static::never())->method('critical');
        $logger->expects(static::never())->method('emergency');
        $logger->expects(static::never())->method('error');
        $logger->expects(static::never())->method('warning');
        $stack->method('next')->willReturnArgument(0);

        $entities = $this->generateEntities(20, 1);

        $result = $receiver->receive($entities, $context, $stack);

        static::assertCount(20, $result);
    }

    public function testAllRetriesAndPartiallySucceeds(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $receiver = new LockingReceiver(FooBarEntity::class(), $logger);
        $stack = $this->createMock(ReceiverStackInterface::class);
        $context = $this->createMock(ReceiveContextInterface::class);

        $logger->expects(static::never())->method('alert');
        $logger->expects(static::once())->method('critical');
        $logger->expects(static::never())->method('emergency');
        $logger->expects(static::never())->method('error');
        $logger->expects(static::never())->method('warning');
        $stack->method('next')->willReturnArgument(0);

        $failingEntities = $this->generateEntities(10, 4);
        $this->lockEntities($failingEntities);

        $entities = $this->generateEntities(10, 1);
        $entities->push($failingEntities);
        $entities->push($this->generateEntities(10, 1));

        $result = $receiver->receive($entities, $context, $stack);

        static::assertCount(20, $result);
    }

    private function generateEntities(int $count, ?int $expectedLockTries = null): TypedDatasetEntityCollection
    {
        $result = new TypedDatasetEntityCollection(FooBarEntity::class());

        for ($counter = 0; $counter < $count; ++$counter) {
            $entity = new FooBarEntity();
            $entity->setPrimaryKey((string) $counter);
            $entity->attach(new LockAttachable($this->generateLock($expectedLockTries)));

            $result->push([$entity]);
        }

        return $result;
    }

    private function generateLock(?int $expectedLockTries = null): LockInterface
    {
        $lockState = false;
        $lock = $this->createMock(LockInterface::class);

        $lock->method('release')->willReturnCallback(static function () use (&$lockState): void {
            $lockState = false;
        });
        $lock->method('isAcquired')->willReturnCallback(static fn () => $lockState);

        if ($expectedLockTries !== null) {
            $acquire = $lock->expects(static::exactly($expectedLockTries))->method('acquire');
        } else {
            $acquire = $lock->method('acquire');
        }

        $acquire->willReturnCallback(static function () use (&$lockState) {
            if ($lockState) {
                return false;
            }

            $lockState = true;

            return true;
        });

        return $lock;
    }

    /**
     * @param iterable<DatasetEntityContract> $entities
     */
    private function lockEntities(iterable $entities): void
    {
        foreach ($entities as $entity) {
            /** @var LockAttachable $attachedLock */
            $attachedLock = $entity->getAttachment(LockAttachable::class);
            static::assertTrue($attachedLock->getLock()->acquire());
        }
    }
}
