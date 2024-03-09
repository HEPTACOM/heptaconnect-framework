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
    public function bySupportedTopic(string $topic): static
    {
        return $this->filter(static fn (StatusReporterContract $reporter) => $reporter->supportsTopic() === $topic);
    }

    /**
     * @psalm-return Contract\StatusReporterContract::class
     */
    protected function getT(): string
    {
        return StatusReporterContract::class;
    }
}
