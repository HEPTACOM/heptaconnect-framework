<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Fail;

interface JobFailActionInterface
{
    public function fail(JobFailPayload $payload): JobFailResult;
}
