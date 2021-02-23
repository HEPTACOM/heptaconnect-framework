<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract;

interface StatusReporterStackInterface
{
    public function next(StatusReportingContextInterface $context): array;
}
