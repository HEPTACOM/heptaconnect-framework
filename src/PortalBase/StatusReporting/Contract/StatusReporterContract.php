<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract;

/**
 * Base class for every status reporter implementation with various boilerplate-reducing entrypoints for rapid development.
 */
abstract class StatusReporterContract
{
    public const TOPIC_HEALTH = 'health';

    public const TOPIC_ANALYSIS = 'analysis';

    public const TOPIC_CONFIG = 'config';

    public const TOPIC_INFO = 'info';

    /**
     * Must return topic that is covered by this implementation.
     * It is not limited to @see TOPIC_HEALTH, TOPIC_ANALYSIS, TOPIC_CONFIG, TOPIC_INFO
     */
    abstract public function supportsTopic(): string;

    /**
     * First entrypoint to report a topic in this flow component.
     * It allows direct stack handling manipulation. @see StatusReporterStackInterface
     * You most likely want to implement @see run instead.
     */
    public function report(StatusReportingContextInterface $context, StatusReporterStackInterface $stack): array
    {
        return \array_merge([$this->supportsTopic() => false], $stack->next($context), $this->run($context));
    }

    /**
     * The entrypoint to report a topic without to be expected to implement stack handling.
     * This is executed when this status reporter on the stack is expected to act.
     * It can be skipped when @see report is implemented accordingly.
     * Returns an array with any status information required for the report.
     * The report must only contain nested scalar values.
     * To confirm a positive status result, add true as value with the supported topic as key in the return value.
     */
    protected function run(StatusReportingContextInterface $context): array
    {
        return [];
    }
}
