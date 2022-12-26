<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Create;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

/**
 * @extends AbstractObjectCollection<JobCreatePayload>
 */
final class JobCreatePayloads extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @param iterable<JobCreatePayload> $items
     */
    public function __construct(iterable $items = [])
    {
        parent::__construct($items);
        $this->attachments = new AttachmentCollection();
    }

    /**
     * @psalm-return JobCreatePayload::class
     */
    protected function getT(): string
    {
        return JobCreatePayload::class;
    }
}
