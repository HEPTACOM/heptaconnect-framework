<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;

abstract class ExplorerContract
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string|int>
     */
    public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
    {
        yield from $this->exploreCurrent($context);

        return $this->exploreNext($stack, $context);
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
        return \is_a($entity, $this->supports(), false) || \is_string($entity) || \is_int($entity);
    }

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string|int>
     */
    final protected function exploreNext(
        ExplorerStackInterface $stack,
        ExploreContextInterface $context
    ): iterable {
        return $stack->next($context);
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
            // TODO: log this
        }
    }

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string|int>
     */
    final protected function exploreNextIfAllowed(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
    {
        try {
            foreach ($this->exploreNext($stack, $context) as $key => $entity) {
                if ($this->isSupported($entity)) {
                    if ($this->performAllowanceCheck($entity, $context)) {
                        yield $key => $entity;
                    }
                } else {
                    throw new UnsupportedDatasetEntityException();
                }
            }
        } catch (\Throwable $exception) {
            // TODO: log this
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

        if ($entity instanceof DatasetEntityContract && !\is_null($entity->getPrimaryKey())) {
            return $this->isAllowed($entity->getPrimaryKey(), $entity, $context);
        }

        return false;
    }

    protected function isAllowed(string $externalId, ?DatasetEntityContract $entity, ExploreContextInterface $context): bool
    {
        return true;
    }
}
