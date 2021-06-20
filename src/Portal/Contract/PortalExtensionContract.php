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
    /**
     * @deprecated Your explorers will be automatically detected. Will be removed in 0.2.0
     */
    final protected function getExplorerDecorators(): ExplorerCollection
    {
        return new ExplorerCollection();
    }

    /**
     * @deprecated Your emitters will be automatically detected. Will be removed in 0.2.0
     */
    final protected function getEmitterDecorators(): EmitterCollection
    {
        return new EmitterCollection();
    }

    /**
     * @deprecated Your receivers will be automatically detected. Will be removed in 0.2.0
     */
    final protected function getReceiverDecorators(): ReceiverCollection
    {
        return new ReceiverCollection();
    }

    /**
     * @deprecated Your status reporter will be automatically detected. Will be removed in 0.2.0
     */
    final protected function getStatusReporterDecorators(): StatusReporterCollection
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

    public function hasAutomaticPsr4Prototyping(): bool
    {
        return true;
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
