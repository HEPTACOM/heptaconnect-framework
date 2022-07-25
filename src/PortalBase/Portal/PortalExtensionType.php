<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

/**
 * @extends SubtypeClassStringContract<PortalExtensionContract>
 */
final class PortalExtensionType extends SubtypeClassStringContract
{
    public function getExpectedSuperClassName(): string
    {
        return PortalExtensionContract::class;
    }
}
