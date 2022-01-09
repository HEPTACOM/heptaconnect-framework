<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;

/**
 * @internal
 */
interface CronjobContextInterface extends PortalNodeContextInterface
{
    public function getCronjob(): CronjobInterface;
}
