<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Reception;

use Heptacom\HeptaConnect\Core\Configuration\Contract\ConfigurationServiceInterface;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceiveContextFactoryInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\EntityStatusContract;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ReceiveContextFactory implements ReceiveContextFactoryInterface
{
    /**
     * @var EventSubscriberInterface[]
     */
    private readonly array $postProcessors;

    public function __construct(
        private readonly ConfigurationServiceInterface $configurationService,
        private readonly PortalStackServiceContainerFactory $portalStackContainerFactory,
        private readonly EntityStatusContract $entityStatus,
        iterable $postProcessors
    ) {
        $this->postProcessors = $postProcessors instanceof \Traversable ? \iterator_to_array($postProcessors) : $postProcessors;
    }

    #[\Override]
    public function createContext(PortalNodeKeyInterface $portalNodeKey): ReceiveContextInterface
    {
        return new ReceiveContext(
            $this->portalStackContainerFactory->create($portalNodeKey),
            $this->configurationService->getPortalNodeConfiguration($portalNodeKey),
            $this->entityStatus,
            $this->postProcessors
        );
    }
}
