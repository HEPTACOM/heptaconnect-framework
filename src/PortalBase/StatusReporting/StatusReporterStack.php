<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StatusReporting;

use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReportingContextInterface;

class StatusReporterStack implements StatusReporterStackInterface
{
    /**
     * @var array<array-key, StatusReporterContract>
     */
    private array $statusReporters;

    /**
     * @param iterable<array-key, StatusReporterContract> $statusReporters
     */
    public function __construct(iterable $statusReporters)
    {
        /** @var StatusReporterContract[] $rewindableStatusReporters */
        $rewindableStatusReporters = \iterable_to_array($statusReporters);
        $this->statusReporters = $rewindableStatusReporters;
    }

    public function next(StatusReportingContextInterface $context): array
    {
        $statusReporter = \array_shift($this->statusReporters);

        if (!$statusReporter instanceof StatusReporterContract) {
            return [];
        }

        return $statusReporter->report($context, $this);
    }
}
