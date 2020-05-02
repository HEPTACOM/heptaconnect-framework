<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

interface EmitContextInterface extends PortalNodeAwareInterface
{
    public function getConfig(MappingInterface $mapping): ?\ArrayAccess;
}
