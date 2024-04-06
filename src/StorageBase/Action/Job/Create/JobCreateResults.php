<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<JobCreateResult>
 */
final class JobCreateResults extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @psalm-return JobCreateResult::class
     */
    protected function getT(): string
    {
        return JobCreateResult::class;
    }
}
