<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<EntityType>
 */
class EntityTypeCollection extends ClassStringReferenceCollection
{
    protected function getT(): string
    {
        return EntityType::class;
    }
}
