<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Exploration;

use Heptacom\HeptaConnect\Core\Exploration\Contract\ExplorerStackBuilderFactoryInterface;
use Heptacom\HeptaConnect\Core\Exploration\Contract\ExplorerStackBuilderInterface;
use Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Psr\Log\LoggerInterface;

final class ExplorerStackBuilderFactory implements ExplorerStackBuilderFactoryInterface
{
    private PortalStackServiceContainerFactory $portalContainerFactory;

    private LoggerInterface $logger;

    public function __construct(PortalStackServiceContainerFactory $portalContainerFactory, LoggerInterface $logger)
    {
        $this->portalContainerFactory = $portalContainerFactory;
        $this->logger = $logger;
    }

    public function createExplorerStackBuilder(
        PortalNodeKeyInterface $portalNodeKey,
        string $entityType
    ): ExplorerStackBuilderInterface {
        $container = $this->portalContainerFactory->create($portalNodeKey);
        /** @var FlowComponentRegistry $flowComponentRegistry */
        $flowComponentRegistry = $container->get(FlowComponentRegistry::class);

        return new ExplorerStackBuilder(
            $flowComponentRegistry->getExplorers(),
            $entityType,
            $this->logger
        );
    }
}
