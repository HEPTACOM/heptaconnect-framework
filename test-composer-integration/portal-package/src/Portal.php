<?php

declare(strict_types=1);

namespace HeptacomFixture\Portal\A;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use HeptacomFixture\Portal\A\Dto\NotEvenService;

class Portal extends PortalContract
{
    public function getContainerExcludedClasses(): array
    {
        return \array_merge(parent::getContainerExcludedClasses(), [
            NotEvenService::class,
        ]);
    }
}
