<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class JobScheduleResult implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private JobKeyCollection $scheduledJobKeys,
        private JobKeyCollection $skippedJobKeys
    ) {
    }

    public function getScheduledJobKeys(): JobKeyCollection
    {
        return $this->scheduledJobKeys;
    }

    public function getSkippedJobKeys(): JobKeyCollection
    {
        return $this->skippedJobKeys;
    }

    public function getAuditableData(): array
    {
        return [
            'scheduledJobKeys' => $this->getScheduledJobKeys(),
            'skippedJobKeys' => $this->getSkippedJobKeys(),
        ];
    }
}
