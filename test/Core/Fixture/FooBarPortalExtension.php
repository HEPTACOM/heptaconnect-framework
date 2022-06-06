<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

final class FooBarPortalExtension extends PortalExtensionContract
{
    public function supports(): string
    {
        return FooBarPortal::class;
    }
}
