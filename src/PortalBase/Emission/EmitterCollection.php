<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract>
 */
class EmitterCollection extends AbstractObjectCollection
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     *
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract>
     */
    public function bySupport(string $entityType): iterable
    {
        return $this->filter(static fn (EmitterContract $emitter) => $entityType === $emitter->supports());
    }

    protected function getT(): string
    {
        return EmitterContract::class;
    }
}
