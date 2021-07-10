<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\EntityStatusContract;

interface ReceiveContextInterface extends PortalNodeContextInterface
{
    public function getEntityStatus(): EntityStatusContract;

    public function markAsFailed(DatasetEntityContract $entity, \Throwable $throwable): void;
}
