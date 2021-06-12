<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterCollection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class PortalContract implements ContainerAwareInterface
{

    private ?ContainerInterface $container;

    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }

    public function setContainer(?ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

    public function getExplorers(): ExplorerCollection
    {
        return new ExplorerCollection();
    }

    public function getEmitters(): EmitterCollection
    {
        return new EmitterCollection();
    }

    public function getReceivers(): ReceiverCollection
    {
        return new ReceiverCollection();
    }

    public function getStatusReporters(): StatusReporterCollection
    {
        return new StatusReporterCollection();
    }

    public function getConfigurationTemplate(): OptionsResolver
    {
        return new OptionsResolver();
    }

    public function getServices(): array
    {
        return [];
    }

    public function getPath(): string
    {
        return \dirname((new \ReflectionClass($this))->getFileName(), 2);
    }
}
