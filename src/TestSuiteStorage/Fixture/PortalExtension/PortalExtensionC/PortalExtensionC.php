<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Fixture\PortalExtension\PortalExtensionC;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalC\PortalC;

class PortalExtensionC extends PortalExtensionContract
{
    /**
     * @psalm-return PortalC::class
     */
    public function supports(): string
    {
        return PortalC::class;
    }
}
