<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<EntityType>
 */
class EntityTypeCollection extends ClassStringReferenceCollection
{
    public function has(EntityType $classString): bool
    {
        foreach ($this->filter([$classString, 'equals']) as $_) {
            return true;
        }

        return false;
    }

    protected function getT(): string
    {
        return EntityType::class;
    }
}
