<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job;

use Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobSchedulePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobScheduleResult;

interface JobScheduleActionInterface
{
    /**
     * Set job states to scheduled.
     */
    public function schedule(JobSchedulePayload $payload): JobScheduleResult;
}
