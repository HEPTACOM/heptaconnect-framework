<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

final class DependentPortalExtension extends PortalExtensionContract
{
    public function __construct(public int $number)
    {
    }

    protected function supports(): string
    {
        return UninstantiablePortal::class;
    }
}
