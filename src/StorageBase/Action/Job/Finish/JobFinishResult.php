<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

final class JobFinishResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private JobKeyCollection $finishedJobs;

    private JobKeyCollection $skippedJobs;

    public function __construct(JobKeyCollection $finishedJobs, JobKeyCollection $skippedJobs)
    {
        $this->attachments = new AttachmentCollection();
        $this->finishedJobs = $finishedJobs;
        $this->skippedJobs = $skippedJobs;
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
