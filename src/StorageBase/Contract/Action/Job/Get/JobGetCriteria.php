<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Get;

use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

class JobGetCriteria
{
    private JobKeyCollection $jobKeys;

    public function __construct(JobKeyCollection $jobKeys)
    {
        $this->jobKeys = $jobKeys;
    }

    public function getJobKeys(): JobKeyCollection
    {
        return $this->jobKeys;
    }

    public function setJobKeys(JobKeyCollection $jobKeys): void
    {
        $this->jobKeys = $jobKeys;
    }
}
