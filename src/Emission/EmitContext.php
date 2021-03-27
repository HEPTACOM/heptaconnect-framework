<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Emission;

use Heptacom\HeptaConnect\Core\Configuration\Contract\ConfigurationServiceInterface;
use Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface;
use Heptacom\HeptaConnect\Core\Portal\AbstractPortalNodeContext;
use Heptacom\HeptaConnect\Core\Portal\Contract\PortalRegistryInterface;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Parallelization\Contract\ResourceLockingContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class EmitContext extends AbstractPortalNodeContext implements EmitContextInterface
{
    private MappingServiceInterface $mappingService;

    public function __construct(
        ConfigurationServiceInterface $configurationService,
        PortalRegistryInterface $portalRegistry,
        PortalStorageFactory $portalStorageFactory,
        ResourceLockingContract $resourceLocking,
        PortalStackServiceContainerFactory $portalStackServiceContainerFactory,
        MappingServiceInterface $mappingService,
        PortalNodeKeyInterface $portalNodeKey
    ) {
        parent::__construct(
            $configurationService,
            $portalRegistry,
            $portalStorageFactory,
            $resourceLocking,
            $portalStackServiceContainerFactory,
            $portalNodeKey
        );
        $this->mappingService = $mappingService;
    }

    public function markAsFailed(MappingInterface $mapping, \Throwable $throwable): void
    {
        $this->mappingService->addException($mapping, $throwable);
    }
}
