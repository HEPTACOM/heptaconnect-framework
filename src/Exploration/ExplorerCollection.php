<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

/**
 * @extends DatasetEntityCollection<\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerInterface>
 */
class ExplorerCollection extends DatasetEntityCollection
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerInterface>
     */
    public function bySupport(string $entityClassName): iterable
    {
        return $this->filter(static function (Contract\ExplorerInterface $explorer) use ($entityClassName): bool {
            return $entityClassName === $explorer->supports();
        });
    }

    protected function getT(): string
    {
        return Contract\ExplorerInterface::class;
    }
}
