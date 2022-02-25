<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Fixture\PortalExtension\PortalExtensionB;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalB\PortalB;

final class PortalExtensionB extends PortalExtensionContract
{
    /**
     * @psalm-return PortalB::class
     */
    public function supports(): string
    {
        return PortalB::class;
    }

    public function isActiveByDefault(): bool
    {
        return false;
    }
}
