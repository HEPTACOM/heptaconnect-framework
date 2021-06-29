<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class PortalContract
{
    use PathMethodsTrait;

    public function getConfigurationTemplate(): OptionsResolver
    {
        return new OptionsResolver();
    }
}
