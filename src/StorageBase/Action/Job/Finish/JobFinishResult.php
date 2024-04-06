<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish;

use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

final class JobFinishResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private JobKeyCollection $finishedJobs,
        private JobKeyCollection $skippedJobs
    ) {
    }

    public function getFinishedJobs(): JobKeyCollection
    {
        return $this->finishedJobs;
    }

    public function getSkippedJobs(): JobKeyCollection
    {
        return $this->skippedJobs;
    }
}
