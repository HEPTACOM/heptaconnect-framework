<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

abstract class UninstantiablePortalExtension extends PortalExtensionContract
{
    protected function supports(): string
    {
        return UninstantiablePortal::class;
    }
}
