<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Listing;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;

final class JobListFinishedResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(private JobKeyInterface $jobKey)
    {
        $this->attachments = new AttachmentCollection();
    }

    public function getJobKey(): JobKeyInterface
    {
        return $this->jobKey;
    }
}
