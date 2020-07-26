<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

/**
 * @extends DatasetEntityCollection<\Heptacom\HeptaConnect\Portal\Base\Contract\EmitterInterface>
 */
class EmitterCollection extends DatasetEntityCollection
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterInterface>
     */
    public function bySupport(string $entityClassName): iterable
    {
        return $this->filter(static function (Contract\EmitterInterface $emitter) use ($entityClassName): bool {
            return \in_array($entityClassName, $emitter->supports(), true);
        });
    }

    protected function getT(): string
    {
        return Contract\EmitterInterface::class;
    }
}
