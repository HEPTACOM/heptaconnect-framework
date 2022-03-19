<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception\CodeOriginNotFound;

interface ReceiverCodeOriginFinderInterface
{
    /**
     * Returns the related code origin to locate the file it was programmed in.
     * It can fail, when the origin can not be determined.
     *
     * @throws CodeOriginNotFound
     */
    public function findOrigin(ReceiverContract $receiver): CodeOrigin;
}
