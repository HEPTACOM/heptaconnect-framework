<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Schedule;

interface JobScheduleActionInterface
{
    public function schedule(JobSchedulePayload $payload): JobScheduleResult;
}
