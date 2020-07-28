<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;

interface PortalAwareInterface
{
    public function getPortal(MappingInterface $mapping): ?PortalContract;
}
