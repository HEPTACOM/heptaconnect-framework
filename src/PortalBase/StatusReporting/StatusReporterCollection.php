<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StatusReporting;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;

/**
 * @extends AbstractObjectCollection<StatusReporterContract>
 */
class StatusReporterCollection extends AbstractObjectCollection
{
    /**
     * @return iterable<StatusReporterContract>
     */
    public function bySupportedTopic(string $topic): iterable
    {
        return $this->filter(static fn (StatusReporterContract $reporter) => $reporter->supportsTopic() === $topic);
    }

    protected function getT(): string
    {
        return StatusReporterContract::class;
    }
}
