<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;

interface EmitContextInterface extends PortalNodeContextInterface
{
    public function isDirectEmission(): bool;

    public function markAsFailed(string $externalId, string $entityType, \Throwable $throwable): void;
}
