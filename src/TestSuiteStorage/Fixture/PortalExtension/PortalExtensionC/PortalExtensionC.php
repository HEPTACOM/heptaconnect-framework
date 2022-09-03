<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Fixture\PortalExtension\PortalExtensionC;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalC\PortalC;

final class PortalExtensionC extends PortalExtensionContract
{
    protected function supports(): string
    {
        return PortalC::class;
    }
}
