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
    /**
     * @param PortalType $portalClass
     */
    public function bySupport(ClassStringReferenceContract $portalClass): self
    {
        return new self($this->filter(
            fn (PortalExtensionContract $extension) => $extension->getSupportedPortal()->same($portalClass)
        ));
    }

    protected function isValidItem($item): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return $item instanceof PortalExtensionContract;
    }
}
