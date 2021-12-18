<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\Finish;

interface JobFinishActionInterface
{
    public function finish(JobFinishPayload $payload): JobFinishResult;
}
