<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<PortalExtensionType>
 */
class PortalExtensionTypeCollection extends ClassStringReferenceCollection
{
    protected function getT(): string
    {
        return PortalExtensionType::class;
    }
}
