<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalAwareInterface;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface;

interface ReceiveContextInterface extends PortalAwareInterface
{
    public function getConfig(MappingInterface $mapping): ?array;

    public function markAsFailed(MappingInterface $mapping, \Throwable $throwable): void;

    public function getStorage(MappingInterface $mapping): PortalStorageInterface;
}
