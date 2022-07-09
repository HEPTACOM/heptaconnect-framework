<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<EntityTypeClassString>
 */
class EntityTypeClassStringCollection extends ClassStringReferenceCollection
{
    public function has(EntityTypeClassString $classString): bool
    {
        foreach ($this->filter([$classString, 'equals']) as $_) {
            return true;
        }

        return false;
    }

    protected function getT(): string
    {
        return EntityTypeClassString::class;
    }
}
