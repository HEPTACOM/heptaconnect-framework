<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class PortalExtensionContract
{
    use PathMethodsTrait;

    public function extendConfiguration(OptionsResolver $template): OptionsResolver
    {
        return $template;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    abstract public function supports(): string;

    public function isActiveByDefault(): bool
    {
        return true;
    }
}
