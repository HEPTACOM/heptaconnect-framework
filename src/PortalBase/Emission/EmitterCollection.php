<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\EntityTypeClassString;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;

/**
 * @extends AbstractObjectCollection<EmitterContract>
 */
class EmitterCollection extends AbstractObjectCollection
{
    /**
     * @return iterable<int, EmitterContract>
     */
    public function bySupport(EntityTypeClassString $entityType): iterable
    {
        return $this->filter(
            static fn (EmitterContract $emitter): bool => $entityType->same($emitter->getSupportedEntityType())
        );
    }

    /**
     * @psalm-return Contract\EmitterContract::class
     */
    protected function getT(): string
    {
        return EmitterContract::class;
    }
}
