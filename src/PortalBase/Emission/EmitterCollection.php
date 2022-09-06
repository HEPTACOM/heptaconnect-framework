<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;

/**
 * @extends AbstractObjectCollection<EmitterContract>
 */
class EmitterCollection extends AbstractObjectCollection
{
    /**
     * @return static
     */
    public function bySupport(EntityType $entityType): self
    {
        return $this->filter(
            static fn (EmitterContract $emitter): bool => $entityType->equals($emitter->getSupportedEntityType())
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
