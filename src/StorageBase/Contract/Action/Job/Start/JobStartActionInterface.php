<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Start;

interface JobStartActionInterface
{
    public function start(JobStartPayload $payload): JobStartResult;
}
