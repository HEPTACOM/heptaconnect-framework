<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract>
 */
class PortalExtensionCollection extends AbstractCollection
{
    public function filterSupported(string $portalClass): self
    {
        return new self($this->filter(
            fn (PortalExtensionContract $extension) => \is_a($extension->supports(), $portalClass, true)
        ));
    }

    protected function isValidItem($item): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return $item instanceof PortalExtensionContract;
    }
}
