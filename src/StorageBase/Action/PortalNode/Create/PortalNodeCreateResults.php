<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

/**
 * @extends AbstractObjectCollection<PortalNodeCreateResult>
 */
final class PortalNodeCreateResults extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @param iterable<PortalNodeCreateResult> $items
     */
    public function __construct(iterable $items = [])
    {
        parent::__construct($items);
        $this->attachments = new AttachmentCollection();
    }

    /**
     * @psalm-return PortalNodeCreateResult::class
     */
    protected function getT(): string
    {
        return PortalNodeCreateResult::class;
    }
}
