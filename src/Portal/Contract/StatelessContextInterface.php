<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Parallelization\Support\ResourceLockFacade;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Psr\Container\ContainerInterface;

interface StatelessContextInterface extends PortalAwareInterface
{
    public function getConfig(MappingInterface $mapping): ?array;

    public function getResourceLocker(): ResourceLockFacade;

    public function getStorage(MappingInterface $mapping): PortalStorageInterface;

    public function getPortalNodeKey(MappingInterface $mapping): PortalNodeKeyInterface;

    public function getContainer(MappingInterface $mapping): ContainerInterface;

    public function markAsFailed(MappingInterface $mapping, \Throwable $throwable): void;
}
