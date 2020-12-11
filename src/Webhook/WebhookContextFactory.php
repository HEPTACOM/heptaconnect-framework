<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Webhook;

use Heptacom\HeptaConnect\Core\Configuration\Contract\ConfigurationServiceInterface;
use Heptacom\HeptaConnect\Core\Portal\Contract\PortalRegistryInterface;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory;
use Heptacom\HeptaConnect\Portal\Base\Parallelization\Contract\ResourceLockingContract;
use Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookInterface;

class WebhookContextFactory
{
    private ConfigurationServiceInterface $configurationService;

    private PortalRegistryInterface $portalRegistry;

    private PortalStorageFactory $portalStorageFactory;

    private ResourceLockingContract $resourceLocking;

    private PortalStackServiceContainerFactory $portalStackServiceContainerFactory;

    public function __construct(
        ConfigurationServiceInterface $configurationService,
        PortalRegistryInterface $portalRegistry,
        PortalStorageFactory $portalStorageFactory,
        ResourceLockingContract $resourceLocking,
        PortalStackServiceContainerFactory $portalStackServiceContainerFactory
    ) {
        $this->configurationService = $configurationService;
        $this->portalRegistry = $portalRegistry;
        $this->portalStorageFactory = $portalStorageFactory;
        $this->resourceLocking = $resourceLocking;
        $this->portalStackServiceContainerFactory = $portalStackServiceContainerFactory;
    }

    public function createContext(WebhookInterface $webhook): WebhookContextInterface
    {
        return new WebhookContext(
            $this->configurationService,
            $this->portalRegistry,
            $this->portalStorageFactory,
            $this->resourceLocking,
            $this->portalStackServiceContainerFactory,
            $webhook->getPortalNodeKey(),
            $webhook
        );
    }
}
