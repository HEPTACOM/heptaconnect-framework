<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract;

abstract class StatusReporterContract
{
    public const TOPIC_HEALTH = 'health';

    public const TOPIC_ANALYSIS = 'analysis';

    public const TOPIC_CONFIG = 'config';

    public const TOPIC_INFO = 'info';

    abstract public function supportsTopic(): string;

    public function report(StatusReportingContextInterface $context, StatusReporterStackInterface $stack): array
    {
        return \array_merge([$this->supportsTopic() => false], $stack->next($context), $this->run($context));
    }

    protected function run(StatusReportingContextInterface $context): array
    {
        return [];
    }
}
