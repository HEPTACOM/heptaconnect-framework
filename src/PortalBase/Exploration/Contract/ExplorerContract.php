<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;
use Psr\Log\LoggerInterface;

abstract class ExplorerContract
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string|int>
     */
    public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
    {
        yield from $this->exploreCurrent($context);
        yield from $this->exploreNext($context, $stack);
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    abstract public function supports(): string;

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string|int>
     */
    protected function run(ExploreContextInterface $context): iterable
    {
        return [];
    }

    /**
     * @param DatasetEntityContract|string|int|null $entity
     */
    final protected function isSupported($entity): bool
    {
        if (\is_null($entity)) {
            return false;
        }

        return \is_string($entity) || \is_int($entity) || \is_a($entity, $this->supports(), false);
    }

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string|int>
     */
    final protected function exploreNext(
        ExploreContextInterface $context,
        ExplorerStackInterface $stack
    ): iterable {
        try {
            foreach ($stack->next($context) as $key => $entity) {
                if ($this->isSupported($entity)) {
                    if ($this->performAllowanceCheck($entity, $context)) {
                        yield $key => $entity;
                    }
                } else {
                    throw new UnsupportedDatasetEntityException();
                }
            }
        } catch (\Throwable $exception) {
            /** @var LoggerInterface $logger */
            $logger = $context->getContainer()->get(LoggerInterface::class);
            $logger->critical(\sprintf(
                'FlowComponent explorer encountered exception in exploreNext(): %s',
                $exception->getMessage()
            ));
        }
    }

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string|int>
     */
    final protected function exploreCurrent(ExploreContextInterface $context): iterable
    {
        try {
            foreach ($this->run($context) as $key => $entity) {
                if ($this->isSupported($entity)) {
                    yield $key => $entity;
                } else {
                    throw new UnsupportedDatasetEntityException();
                }
            }
        } catch (\Throwable $exception) {
            /** @var LoggerInterface $logger */
            $logger = $context->getContainer()->get(LoggerInterface::class);
            $logger->critical(\sprintf(
                'FlowComponent explorer encountered exception in exploreCurrent(): %s',
                $exception->getMessage()
            ));
        }
    }

    /**
     * @param DatasetEntityContract|string|int $entity
     */
    final protected function performAllowanceCheck($entity, ExploreContextInterface $context): bool
    {
        if (\is_string($entity)) {
            return $this->isAllowed($entity, null, $context);
        }

        if (\is_int($entity)) {
            return $this->isAllowed((string) $entity, null, $context);
        }

        $primaryKey = $entity->getPrimaryKey();

        if ($entity instanceof DatasetEntityContract && !\is_null($primaryKey)) {
            return $this->isAllowed($primaryKey, $entity, $context);
        }

        return false;
    }

    protected function isAllowed(string $externalId, ?DatasetEntityContract $entity, ExploreContextInterface $context): bool
    {
        return true;
    }
}
