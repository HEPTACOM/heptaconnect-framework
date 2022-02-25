<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract;

interface StatusReporterStackInterface
{
    /**
     * Forwards the status reporting to the next status reporter on the stack and returns its response.
     */
    public function next(StatusReportingContextInterface $context): array;
}
