<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines a portal.
 * It needs to be present for a portal to work as a reference point on the disk.
 */
abstract class PortalContract
{
    use PathMethodsTrait;

    /**
     * Returns structure, validation and default value schemes to validate configuration for any portal node.
     */
    public function getConfigurationTemplate(): OptionsResolver
    {
        return new OptionsResolver();
    }

    /**
     * Returns a class string instance for the type of the extending class.
     */
    final public static function class(): PortalType
    {
        return new PortalType(static::class);
    }
}
