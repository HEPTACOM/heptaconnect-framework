<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

interface ReceiveContextInterface extends PortalNodeAwareInterface
{
    public function markAsFailed(MappingInterface $mapping, \Throwable $throwable): void;
}
