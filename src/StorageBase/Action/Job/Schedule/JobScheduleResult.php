<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

final class JobScheduleResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private JobKeyCollection $scheduledJobs;

    private JobKeyCollection $skippedJobs;

    public function __construct(JobKeyCollection $scheduledJobs, JobKeyCollection $skippedJobs)
    {
        $this->attachments = new AttachmentCollection();
        $this->scheduledJobs = $scheduledJobs;
        $this->skippedJobs = $skippedJobs;
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
