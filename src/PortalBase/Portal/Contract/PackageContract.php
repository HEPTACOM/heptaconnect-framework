<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This contract must only be extended by @see PortalContract and @see PortalExtensionContract
 * Its only purpose is to combine their features in a single class.
 *
 * @internal
 */
abstract class PackageContract
{
    use PathMethodsTrait;

    /**
     * Returns all FQCNs that must not to be present in a service's class hierarchy.
     * Useful to exclude interfaces and base classes used by DTOs that should not be part of the portal node container.
     * The result will only affect services auto-prototyped for this package.
     *
     * @return class-string[]
     */
    public function getContainerExcludedClasses(): array
    {
        return [
            \Throwable::class,
            DatasetEntityContract::class,
            CollectionInterface::class,
            AttachableInterface::class,
        ];
    }

    public function buildContainer(ContainerBuilder $containerBuilder): void
    {
    }
}
