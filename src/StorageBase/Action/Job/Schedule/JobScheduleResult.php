<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule;

use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

class JobScheduleResult
{
    private JobKeyCollection $scheduledJobs;

    private JobKeyCollection $skippedJobs;

    public function __construct(JobKeyCollection $scheduledJobs, JobKeyCollection $skippedJobs)
    {
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
