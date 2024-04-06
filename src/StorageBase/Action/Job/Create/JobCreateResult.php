<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

final class JobCreateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private JobKeyInterface $jobKey
    ) {
    }

    public function getJobKey(): JobKeyInterface
    {
        return $this->jobKey;
    }
}
