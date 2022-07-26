<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractClassStringReferenceCollection;

/**
 * @extends AbstractClassStringReferenceCollection<PortalType>
 */
final class PortalTypeCollection extends AbstractClassStringReferenceCollection
{
    protected function getT(): string
    {
        return PortalType::class;
    }
}
