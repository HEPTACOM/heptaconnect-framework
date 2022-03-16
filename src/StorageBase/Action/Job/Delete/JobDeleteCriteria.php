<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Delete;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

final class JobDeleteCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private JobKeyCollection $jobKeys;

    public function __construct(JobKeyCollection $jobKeys)
    {
        $this->attachments = new AttachmentCollection();
        $this->jobKeys = $jobKeys;
    }

    public function getJobKeys(): JobKeyCollection
    {
        return $this->jobKeys;
    }

    public function setJobKeys(JobKeyCollection $jobKeys): void
    {
        $this->jobKeys = $jobKeys;
    }
}
