<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;

/**
 * @extends SubtypeClassStringContract<PortalContract>
 *
 * @phpstan-method class-string<PortalContract> __toString()
 * @phpstan-method class-string<PortalContract> jsonSerialize()
 */
final class PortalType extends SubtypeClassStringContract
{
    public function getExpectedSuperClassName(): string
    {
        return PortalContract::class;
    }
}
