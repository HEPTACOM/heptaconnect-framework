<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartResult;

interface JobStartActionInterface
{
    /**
     * Set job states to started.
     */
    public function start(JobStartPayload $payload): JobStartResult;
}
