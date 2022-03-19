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

    private JobKeyCollection $failedJobs;

    private JobKeyCollection $skippedJobs;

    public function __construct(JobKeyCollection $failedJobs, JobKeyCollection $skippedJobs)
    {
        $this->attachments = new AttachmentCollection();
        $this->failedJobs = $failedJobs;
        $this->skippedJobs = $skippedJobs;
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
