<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class PortalExtensionContract
{
    public function getExplorerDecorators(): ExplorerCollection
    {
        return new ExplorerCollection();
    }

    public function getEmitterDecorators(): EmitterCollection
    {
        return new EmitterCollection();
    }

    public function getReceiverDecorators(): ReceiverCollection
    {
        return new ReceiverCollection();
    }

    public function getStatusReporterDecorators(): StatusReporterCollection
    {
        return new StatusReporterCollection();
    }

    public function extendConfiguration(OptionsResolver $template): OptionsResolver
    {
        return $template;
    }

    public function getPath(): string
    {
        /** @var string $path */
        $path = (new \ReflectionClass($this))->getFileName();

        return \dirname($path, 2);
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    abstract public function supports(): string;

    /**
     * @deprecated You should use services.xml / services.yml under ../resources/config. Will be removed in 0.2.0
     */
    final protected function extendServices(array $services): array
    {
        return $services;
    }
}
