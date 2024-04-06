<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Contract\AttachmentAwareInterface;

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
