<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReportingContextInterface;

final class FooBarStatusReporter extends StatusReporterContract
{
    public function __construct(
        private readonly string $id
    ) {
    }

    #[\Override]
    public function supportsTopic(): string
    {
        return 'foo-bar';
    }

    #[\Override]
    protected function run(StatusReportingContextInterface $context): array
    {
        return [$this->supportsTopic() . '.' . $this->id => true];
    }
}
