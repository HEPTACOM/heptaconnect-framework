<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

final class JobFailResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private JobKeyCollection $failedJobs,
        private JobKeyCollection $skippedJobs
    ) {
        $this->attachments = new AttachmentCollection();
    }

    public function getFailedJobs(): JobKeyCollection
    {
        return $this->failedJobs;
    }

    public function getSkippedJobs(): JobKeyCollection
    {
        return $this->skippedJobs;
    }
}
