<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

class DependentPortalExtension extends PortalExtensionContract
{
    public int $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function supports(): string
    {
        return UninstantiablePortal::class;
    }
}
