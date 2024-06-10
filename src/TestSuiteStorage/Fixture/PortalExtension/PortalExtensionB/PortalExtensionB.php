<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Fixture\PortalExtension\PortalExtensionB;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalB\PortalB;

final class PortalExtensionB extends PortalExtensionContract
{
    #[\Override]
    public function isActiveByDefault(): bool
    {
        return false;
    }

    #[\Override]
    protected function supports(): string
    {
        return PortalB::class;
    }
}
