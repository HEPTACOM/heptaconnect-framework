<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;

abstract class ExplorerContract
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string>
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
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string>
     */
    protected function run(ExploreContextInterface $context): iterable
    {
        return [];
    }

    final protected function isSupported(DatasetEntityContract $entity): bool
    {
        return \is_a($entity, $this->supports(), false);
    }

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string>
     */
    final protected function exploreNext(
        ExplorerStackInterface $stack,
        ExploreContextInterface $context
    ): iterable {
        return $stack->next($context);
    }

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string>
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
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string>
     */
    final protected function exploreNextIfAllowed(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
    {
        try {
            foreach ($this->exploreNext($stack, $context) as $key => $entity) {
                if ($this->isSupported($entity)) {
                    if ($this->isAllowed($entity, $context)) {
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

    protected function isAllowed(DatasetEntityContract $entity, ExploreContextInterface $context): bool
    {
        return true;
    }
}
