<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StatusReporting;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;

/**
 * @extends DatasetEntityCollection<\Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract>
 */
class StatusReporterCollection extends DatasetEntityCollection
{
    /**
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract>
     */
    public function bySupportedTopic(string $topic): iterable
    {
        return $this->filter(fn (StatusReporterContract $reporter) => $reporter->supportsTopic() === $topic);
    }

    protected function getT(): string
    {
        return StatusReporterContract::class;
    }
}
