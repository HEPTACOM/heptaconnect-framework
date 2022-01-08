<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartResult;

interface JobStartActionInterface
{
    public function start(JobStartPayload $payload): JobStartResult;
}
