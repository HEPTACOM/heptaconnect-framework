<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Support;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeAliasNotFoundException;

interface PortalNodeAliasResolverInterface
{
    /**
     * Looks up a portal node by the given portal node alias.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws PortalNodeAliasNotFoundException
     */
    public function resolve(string $portalNodeAlias): PortalNodeKeyInterface;
}
