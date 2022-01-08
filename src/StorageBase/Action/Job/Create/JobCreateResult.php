<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Create;

use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;

class JobCreateResult
{
    protected JobKeyInterface $jobKey;

    public function __construct(JobKeyInterface $jobKey)
    {
        $this->jobKey = $jobKey;
    }

    public function getJobKey(): JobKeyInterface
    {
        return $this->jobKey;
    }
}
