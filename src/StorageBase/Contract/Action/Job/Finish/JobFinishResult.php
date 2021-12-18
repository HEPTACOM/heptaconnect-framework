<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Finish;

use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

class JobFinishResult
{
    private JobKeyCollection $finishedJobs;

    private JobKeyCollection $skippedJobs;

    public function __construct(JobKeyCollection $finishedJobs, JobKeyCollection $skippedJobs)
    {
        $this->finishedJobs = $finishedJobs;
        $this->skippedJobs = $skippedJobs;
    }

    public function getFinishedJobs(): JobKeyCollection
    {
        return $this->finishedJobs;
    }

    public function getSkippedJobs(): JobKeyCollection
    {
        return $this->skippedJobs;
    }
}
