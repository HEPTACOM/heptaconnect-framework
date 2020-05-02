<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

interface PortalNodeAwareInterface
{
    public function getPortalNode(MappingInterface $mapping): ?PortalNodeInterface;
}
