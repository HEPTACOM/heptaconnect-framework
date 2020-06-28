<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;

trait AttachmentAwareTrait
{
    protected AttachmentCollection $attachments;

    public function getAttachments(): AttachmentCollection
    {
        return $this->attachments;
    }

    public function attach(DatasetEntityInterface $attachment): void
    {
        $this->attachments->push([$attachment]);
    }

    public function hasAttached(string $class): bool
    {
        return $this->attachments->filter(
            fn (DatasetEntityInterface $entity): bool => $entity instanceof $class
        )->valid();
    }

    public function getAttachment(string $class): ?DatasetEntityInterface
    {
        $iterator = $this->attachments->filter(
            fn (DatasetEntityInterface $entity): bool => $entity instanceof $class
        );

        return $iterator->valid() ? $iterator->current() : null;
    }
}
