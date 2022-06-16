<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract;

/**
 * Identifies a portal node storage key.
 */
interface PortalNodeKeyInterface extends StorageKeyInterface
{
    /**
     * Returns an instance of @see PortalNodeKeyInterface that is expected to have an alias
     * and **MUST** be represented by its alias whenever possible.
     */
    public function withAlias(): PortalNodeKeyInterface;

    /**
     * Returns an instance of @see PortalNodeKeyInterface that is expected to have an alias
     * and **MUST NOT** be represented by its alias whenever possible.
     */
    public function withoutAlias(): PortalNodeKeyInterface;
}
