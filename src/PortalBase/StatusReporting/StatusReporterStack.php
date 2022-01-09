<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StatusReporting;

use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReportingContextInterface;
use Psr\Log\LoggerInterface;

class StatusReporterStack implements StatusReporterStackInterface
{
    /**
     * @var array<array-key, StatusReporterContract>
     */
    private array $statusReporters;

    private LoggerInterface $logger;

    /**
     * @param iterable<array-key, StatusReporterContract> $statusReporters
     */
    public function __construct(iterable $statusReporters, LoggerInterface $logger)
    {
        /** @var StatusReporterContract[] $rewindableStatusReporters */
        $rewindableStatusReporters = \iterable_to_array($statusReporters);
        $this->statusReporters = $rewindableStatusReporters;
        $this->logger = $logger;
    }

    public function next(StatusReportingContextInterface $context): array
    {
        $statusReporter = \array_shift($this->statusReporters);

        if (!$statusReporter instanceof StatusReporterContract) {
            return [];
        }

        $this->logger->debug('Execute FlowComponent status reporter', [
            'status_reporter' => $statusReporter,
        ]);

        return $statusReporter->report($context, $this);
    }
}
