<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception\CodeOriginNotFound;

interface HttpHandlerCodeOriginFinderInterface
{
    /**
     * @throws CodeOriginNotFound
     */
    public function findOrigin(HttpHandlerContract $httpHandler): CodeOrigin;
}
