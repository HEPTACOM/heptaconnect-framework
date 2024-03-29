<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReportingContextInterface;

final class FooBarStatusReporter extends StatusReporterContract
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function supportsTopic(): string
    {
        return 'foo-bar';
    }

    protected function run(StatusReportingContextInterface $context): array
    {
        return [$this->supportsTopic() . '.' . $this->id => true];
    }
}
