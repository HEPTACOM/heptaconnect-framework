<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish\JobFinishPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish\JobFinishResult;

interface JobFinishActionInterface
{
    /**
     * Set job states to finished.
     */
    public function finish(JobFinishPayload $payload): JobFinishResult;
}
