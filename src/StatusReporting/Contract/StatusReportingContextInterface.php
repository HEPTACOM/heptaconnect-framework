<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract;

use Heptacom\HeptaConnect\Portal\Base\Parallelization\Support\ResourceLockFacade;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface;

interface StatusReportingContextInterface
{
    public function getConfig(): ?array;

    public function getStorage(): PortalStorageInterface;

    public function getResourceLocker(): ResourceLockFacade;

    public function getPortal(): PortalContract;
}
