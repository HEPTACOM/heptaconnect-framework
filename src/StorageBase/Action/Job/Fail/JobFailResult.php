<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail;

use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

class JobFailResult
{
    private JobKeyCollection $failedJobs;

    private JobKeyCollection $skippedJobs;

    public function __construct(JobKeyCollection $failedJobs, JobKeyCollection $skippedJobs)
    {
        $this->failedJobs = $failedJobs;
        $this->skippedJobs = $skippedJobs;
    }

    public function getFailedJobs(): JobKeyCollection
    {
        return $this->failedJobs;
    }

    public function getSkippedJobs(): JobKeyCollection
    {
        return $this->skippedJobs;
    }
}
