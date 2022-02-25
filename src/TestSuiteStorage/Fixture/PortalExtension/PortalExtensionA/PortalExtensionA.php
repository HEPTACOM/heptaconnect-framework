<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Fixture\PortalExtension\PortalExtensionA;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;

class PortalExtensionA extends PortalExtensionContract
{
    /**
     * @psalm-return PortalA::class
     */
    public function supports(): string
    {
        return PortalA::class;
    }
}
