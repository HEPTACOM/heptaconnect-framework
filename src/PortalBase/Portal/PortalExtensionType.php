<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;

/**
 * @extends SubtypeClassStringContract<PortalExtensionContract>
 *
 * @psalm-method class-string<PortalExtensionContract> __toString()
 * @psalm-method class-string<PortalExtensionContract> jsonSerialize()
 */
final class PortalExtensionType extends SubtypeClassStringContract
{
    public function getExpectedSuperClassName(): string
    {
        return PortalExtensionContract::class;
    }
}
