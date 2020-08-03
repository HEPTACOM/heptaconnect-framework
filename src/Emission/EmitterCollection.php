<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection<\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract>
 */
class EmitterCollection extends DatasetEntityCollection
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract>
     */
    public function bySupport(string $entityClassName): iterable
    {
        return $this->filter(fn (EmitterContract $emitter) => \in_array($entityClassName, $emitter->supports(), true));
    }

    protected function getT(): string
    {
        return EmitterContract::class;
    }
}
