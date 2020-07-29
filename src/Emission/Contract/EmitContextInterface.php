<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalAwareInterface;

interface EmitContextInterface extends PortalAwareInterface
{
    public function getConfig(MappingInterface $mapping): ?array;
}
