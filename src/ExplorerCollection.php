<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

class ExplorerCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return Contract\ExplorerInterface::class;
    }

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Contract\ExplorerInterface>
     */
    public function bySupport(string $entityClassName): iterable
    {
        yield from $this->filter(function (Contract\ExplorerInterface $explorer) use ($entityClassName): bool {
            return $entityClassName === $explorer->supports();
        });
    }
}
