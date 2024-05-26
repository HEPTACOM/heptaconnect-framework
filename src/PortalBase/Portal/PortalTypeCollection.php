<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Utility\ClassString\AbstractClassStringReferenceCollection;

/**
 * @extends AbstractClassStringReferenceCollection<PortalType>
 */
final class PortalTypeCollection extends AbstractClassStringReferenceCollection
{
    #[\Override]
    protected function getT(): string
    {
        return PortalType::class;
    }
}
