<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;

abstract class ExplorerContract
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
    {
        $supports = $this->supports();

        try {
            foreach ($this->run($context->getPortal(), $context) as $key => $entity) {
                if (\is_a($entity, $supports, false)) {
                    yield $key => $entity;
                } else {
                    throw new UnsupportedDatasetEntityException();
                }
            }
        } catch (\Throwable $exception) {
            // TODO: log this
        }

        return $stack->next($context);
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    abstract public function supports(): string;

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    protected function run(PortalContract $portal, ExploreContextInterface $context): iterable
    {
        return [];
    }
}
