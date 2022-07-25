<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines a portal extension.
 * It needs to be present for a portal extension to work as a reference point on the disk.
 */
abstract class PortalExtensionContract
{
    use PathMethodsTrait;

    /**
     * Change the portal configuration structure, validation and default value schemes for any portal node this extension is combined with.
     */
    public function extendConfiguration(OptionsResolver $template): OptionsResolver
    {
        return $template;
    }

    /**
     * Must return the supported portal.
     *
     * @return class-string<PortalContract>
     */
    abstract public function supports(): string;

    /**
     * Returns true, when it must be active for any supported portal nodes, if not configured otherwise.
     */
    public function isActiveByDefault(): bool
    {
        return true;
    }

    /**
     * Returns a class string instance for the type of the extending class.
     */
    final public static function class(): PortalExtensionType
    {
        return new PortalExtensionType(static::class);
    }
}
