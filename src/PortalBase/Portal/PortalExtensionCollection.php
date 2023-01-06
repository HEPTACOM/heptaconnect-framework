<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

/**
 * @extends AbstractCollection<PortalExtensionContract>
 */
class PortalExtensionCollection extends AbstractCollection
{
    public function bySupport(ClassStringReferenceContract $portalClass): static
    {
        return $this->filter(
            fn (PortalExtensionContract $extension) => $extension->getSupportedPortal()->isTypeOfClassString($portalClass)
        );
    }

    protected function isValidItem(mixed $item): bool
    {
        return $item instanceof PortalExtensionContract;
    }
}
