<?php

declare(strict_types=1);

namespace HeptacomFixture\Portal\Extension;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

class PortalExtension extends PortalExtensionContract
{
    #[\Override]
    protected function supports(): string
    {
        return \HeptacomFixture\Portal\A\Portal::class;
    }
}
