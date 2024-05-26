<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Create;

use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<JobCreatePayload>
 */
final class JobCreatePayloads extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    #[\Override]
    protected function getT(): string
    {
        return JobCreatePayload::class;
    }
}
