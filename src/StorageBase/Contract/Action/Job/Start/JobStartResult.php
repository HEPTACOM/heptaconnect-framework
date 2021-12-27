<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Start;

use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

class JobStartResult
{
    private JobKeyCollection $startedJobs;

    private JobKeyCollection $skippedJobs;

    public function __construct(JobKeyCollection $startedJobs, JobKeyCollection $skippedJobs)
    {
        $this->startedJobs = $startedJobs;
        $this->skippedJobs = $skippedJobs;
    }

    public function getStartedJobs(): JobKeyCollection
    {
        return $this->startedJobs;
    }

    public function getSkippedJobs(): JobKeyCollection
    {
        return $this->skippedJobs;
    }
}
