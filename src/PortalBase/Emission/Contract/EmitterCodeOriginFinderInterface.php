<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception\CodeOriginNotFound;

interface EmitterCodeOriginFinderInterface
{
    /**
     * @throws CodeOriginNotFound
     */
    public function findOrigin(EmitterContract $emitter): CodeOrigin;
}
