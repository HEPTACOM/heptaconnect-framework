<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;

/**
 * @extends AbstractObjectCollection<EmitterContract>
 */
class EmitterCollection extends AbstractObjectCollection
{
    /**
     * @param class-string<DatasetEntityContract> $entityType
     *
     * @return iterable<EmitterContract>
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
