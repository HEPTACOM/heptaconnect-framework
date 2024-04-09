<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Utility\ClassString\AbstractClassStringReferenceCollection;

/**
 * @extends AbstractClassStringReferenceCollection<PortalExtensionType>
 */
final class PortalExtensionTypeCollection extends AbstractClassStringReferenceCollection
{
    protected function getT(): string
    {
        return PortalExtensionType::class;
    }
}
