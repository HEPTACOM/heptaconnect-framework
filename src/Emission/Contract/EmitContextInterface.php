<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

interface EmitContextInterface extends PortalNodeAwareInterface
{
    /**
     * @psalm-return \ArrayAccess<array-key, mixed>|null
     */
    public function getConfig(MappingInterface $mapping): ?\ArrayAccess;
}
