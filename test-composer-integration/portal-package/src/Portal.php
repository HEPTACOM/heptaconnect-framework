<?php

declare(strict_types=1);

namespace HeptacomFixture\Portal\A;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use HeptacomFixture\Portal\A\Dto\ShouldNotBeAService;
use HeptacomFixture\Portal\AdditionalPackage\AdditionalPackage;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class Portal extends PortalContract
{
    public function getContainerExcludedClasses(): array
    {
        return \array_merge(parent::getContainerExcludedClasses(), [
            ShouldNotBeAService::class,
        ]);
    }

    public function buildContainer(ContainerBuilder $containerBuilder): void
    {
        parent::buildContainer($containerBuilder);

        $this->setSyntheticServices($containerBuilder, [
            'manual-service-by-portal' => new class() {
            },
        ]);
    }

    public function getAdditionalPackages(): iterable
    {
        yield new AdditionalPackage();
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
