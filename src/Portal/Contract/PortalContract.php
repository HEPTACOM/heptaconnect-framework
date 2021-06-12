<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class PortalContract
{
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

    /**
     * @deprecated Your status reporter will be automatically detected. Will be removed in 0.2.0
     */
    final protected function getStatusReporters(): StatusReporterCollection
    {
        return new StatusReporterCollection();
    }

    public function getConfigurationTemplate(): OptionsResolver
    {
        return new OptionsResolver();
    }

    public function getPath(): string
    {
        /** @var string $path */
        $path = (new \ReflectionClass($this))->getFileName();

        return \dirname($path, 2);
    }

    /**
     * @deprecated You should use services.xml / services.yml under ../resources/config. Will be removed in 0.2.0
     */
    final protected function getServices(): array
    {
        return [];
    }
}
