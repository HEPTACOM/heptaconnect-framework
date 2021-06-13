<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
 */
class PortalCollection extends AbstractCollection
{
    public function getReceivers(): ReceiverCollection
    {
        $result = new ReceiverCollection();

        /** @var PortalContract $extension */
        foreach ($this->getIterator() as $extension) {
            $result->push($extension->getReceivers()->getIterator());
        }

        return $result;
    }

    protected function isValidItem($item): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return $item instanceof PortalContract;
    }
}
