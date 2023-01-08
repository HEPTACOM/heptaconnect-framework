<?php

declare(strict_types=1);

namespace HeptacomFixture\Portal\A;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use HeptacomFixture\Portal\A\Dto\ShouldNotBeAService;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Portal extends PortalContract
{
    public function getContainerExcludedClasses(): array
    {
        return \array_merge(parent::getContainerExcludedClasses(), [
            ShouldNotBeAService::class,
        ]);
    }

    public function getConfigurationTemplate(): OptionsResolver
    {
        return parent::getConfigurationTemplate()
            ->setDefault('timeInterval', 1)
            ->setAllowedTypes('timeInterval', ['int', 'string'])
        ;
    }
}
