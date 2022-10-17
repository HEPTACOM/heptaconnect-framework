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

    public function __construct(private JobKeyCollection $startedJobs, private JobKeyCollection $skippedJobs)
    {
        $this->attachments = new AttachmentCollection();
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
