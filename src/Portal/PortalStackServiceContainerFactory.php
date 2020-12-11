<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Portal;

use Heptacom\HeptaConnect\Core\Portal\Contract\PortalRegistryInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;

class PortalStackServiceContainerFactory
{
    private PortalRegistryInterface $portalRegistry;

    private PortalStackServiceContainerBuilder $portalStackServiceContainerBuilder;

    private StorageKeyGeneratorContract $storageKeyGenerator;

    private array $portalContainers = [];

    public function __construct(
        PortalRegistryInterface $portalRegistry,
        PortalStackServiceContainerBuilder $portalStackServiceContainerBuilder,
        StorageKeyGeneratorContract $storageKeyGenerator
    ) {
        $this->portalRegistry = $portalRegistry;
        $this->portalStackServiceContainerBuilder = $portalStackServiceContainerBuilder;
        $this->storageKeyGenerator = $storageKeyGenerator;
    }

    public function create(PortalNodeKeyInterface $portalNodeKey): PortalStackServiceContainer
    {
        $key = $this->storageKeyGenerator->serialize($portalNodeKey);
        $result = $this->portalContainers[$key] ?? null;

        if ($result instanceof PortalStackServiceContainer) {
            return $result;
        }

        $result = $this->portalStackServiceContainerBuilder->build(
            $this->portalRegistry->getPortal($portalNodeKey),
            $this->portalRegistry->getPortalExtensions($portalNodeKey)
        );
        $this->portalContainers[$key] = $result;

        return $result;
    }
}
