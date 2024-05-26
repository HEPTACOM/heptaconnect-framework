<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Utility\ClassString\AbstractClassStringReferenceCollection;

/**
 * @extends AbstractClassStringReferenceCollection<EntityType>
 */
final class EntityTypeCollection extends AbstractClassStringReferenceCollection
{
    #[\Override]
    protected function getT(): string
    {
        return EntityType::class;
    }
}
