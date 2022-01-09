<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract;

use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception\CodeOriginNotFound;

interface StatusReporterCodeOriginFinderInterface
{
    /**
     * @throws CodeOriginNotFound
     */
    public function findOrigin(StatusReporterContract $statusReporter): CodeOrigin;
}
