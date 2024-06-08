<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Delete;

use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

final class JobDeleteCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private JobKeyCollection $jobKeys
    ) {
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
