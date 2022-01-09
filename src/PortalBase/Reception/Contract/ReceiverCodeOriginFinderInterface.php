<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception\CodeOriginNotFound;

interface ReceiverCodeOriginFinderInterface
{
    /**
     * @throws CodeOriginNotFound
     */
    public function findOrigin(ReceiverContract $receiver): CodeOrigin;
}
