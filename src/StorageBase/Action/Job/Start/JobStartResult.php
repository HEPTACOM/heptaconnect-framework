<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Start;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

final class JobStartResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private JobKeyCollection $startedJobs;

    private JobKeyCollection $skippedJobs;

    public function __construct(JobKeyCollection $startedJobs, JobKeyCollection $skippedJobs)
    {
        $this->attachments = new AttachmentCollection();
        $this->startedJobs = $startedJobs;
        $this->skippedJobs = $skippedJobs;
    }

    public function getStartedJobs(): JobKeyCollection
    {
        return $this->startedJobs;
    }

    public function getSkippedJobs(): JobKeyCollection
    {
        return $this->skippedJobs;
    }
}
