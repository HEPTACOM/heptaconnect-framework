<?php

declare(strict_types=1);

namespace HeptacomFixture\Portal\AdditionalPackage;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AdditionalPackage extends PackageContract
{
    public function buildContainer(ContainerBuilder $containerBuilder): void
    {
        $this->setSyntheticServices($containerBuilder, [
            'manual-service-by-additional-package' => new class () {},
        ]);
    }

    /**
     * @param object[] $services
     */
    private function setSyntheticServices(ContainerBuilder $containerBuilder, array $services): void
    {
        foreach ($services as $id => $service) {
            $definitionId = (string) $id;
            $containerBuilder->set($definitionId, $service);
            $definition = (new Definition())
                ->setSynthetic(true)
                ->setClass(\get_class($service));
            $containerBuilder->setDefinition($definitionId, $definition);
        }
    }
}
