<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Parallelization\Support\ResourceLockFacade;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalAwareInterface;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface;

interface EmitContextInterface extends PortalAwareInterface
{
    public function getConfig(MappingInterface $mapping): ?array;

    public function getStorage(MappingInterface $mapping): PortalStorageInterface;

    public function markAsFailed(MappingInterface $mapping, \Throwable $throwable): void;

    public function getResourceLocker(): ResourceLockFacade;
}
