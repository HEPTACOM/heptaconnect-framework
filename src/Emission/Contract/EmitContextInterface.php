<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;

interface EmitContextInterface extends PortalNodeContextInterface
{
    public function markAsFailed(string $externalId, string $datasetEntityClassName, \Throwable $throwable): void;
}
