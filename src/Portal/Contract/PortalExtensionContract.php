<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class PortalExtensionContract
{
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
}
