<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;

interface PortalNodeAwareInterface
{
    public function getPortalNode(MappingInterface $mapping): ?PortalNodeInterface;
}
