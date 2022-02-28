<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail\JobFailPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail\JobFailResult;

interface JobFailActionInterface
{
    /**
     * Set job states to failed.
     */
    public function fail(JobFailPayload $payload): JobFailResult;
}
