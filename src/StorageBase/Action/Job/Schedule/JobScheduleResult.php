<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule;

use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

final class JobScheduleResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private JobKeyCollection $scheduledJobs,
        private JobKeyCollection $skippedJobs
    ) {
    }

    public function getScheduledJobs(): JobKeyCollection
    {
        return $this->scheduledJobs;
    }

    public function getSkippedJobs(): JobKeyCollection
    {
        return $this->skippedJobs;
    }
}
