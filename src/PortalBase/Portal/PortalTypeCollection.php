<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<PortalType>
 */
class PortalTypeCollection extends ClassStringReferenceCollection
{
    protected function getT(): string
    {
        return PortalType::class;
    }
}
