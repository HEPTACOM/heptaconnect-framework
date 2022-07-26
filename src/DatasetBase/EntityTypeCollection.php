<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractClassStringReferenceCollection;

/**
 * @extends AbstractClassStringReferenceCollection<EntityType>
 */
final class EntityTypeCollection extends AbstractClassStringReferenceCollection
{
    protected function getT(): string
    {
        return EntityType::class;
    }
}
