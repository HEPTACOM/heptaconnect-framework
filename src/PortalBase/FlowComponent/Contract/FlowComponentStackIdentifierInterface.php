<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\FlowComponent\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

interface FlowComponentStackIdentifierInterface extends \JsonSerializable
{
    /**
     * Returns the portal node key of the stack.
     */
    public function getPortalNodeKey(): PortalNodeKeyInterface;
}
