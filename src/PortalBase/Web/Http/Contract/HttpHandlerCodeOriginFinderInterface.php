<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception\CodeOriginNotFound;

interface HttpHandlerCodeOriginFinderInterface
{
    /**
     * Returns the related code origin to locate the file it was programmed in.
     * It can fail, when the origin can not be determined.
     *
     * @throws CodeOriginNotFound
     */
    public function findOrigin(HttpHandlerContract $httpHandler): CodeOrigin;
}
