<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Parallelization\Support\ResourceLockFacade;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalAwareInterface;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

interface ReceiveContextInterface extends PortalAwareInterface
{
    public function getPortalNodeKey(MappingInterface $mapping): PortalNodeKeyInterface;

    public function getConfig(MappingInterface $mapping): ?array;

    public function markAsFailed(MappingInterface $mapping, \Throwable $throwable): void;

    public function getStorage(MappingInterface $mapping): PortalStorageInterface;

    public function getResourceLocker(): ResourceLockFacade;
}
