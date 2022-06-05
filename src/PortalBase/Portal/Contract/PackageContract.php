<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

/**
 * This contract must only be extended by @see PortalContract and @see PortalExtensionContract
 * Its only purpose is to combine their features in a single class.
 *
 * @internal
 */
abstract class PackageContract
{
    use PathMethodsTrait;
}
