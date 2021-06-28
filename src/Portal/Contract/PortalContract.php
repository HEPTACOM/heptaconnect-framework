<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class PortalContract
{
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

    public function hasAutomaticPsr4Prototyping(): bool
    {
        return true;
    }
}
