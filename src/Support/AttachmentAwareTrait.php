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
        $className = \get_class($attachment);

        if ($this->hasAttached($className)) {
            $this->unattach($className);
        }

        $this->attachments->push([$attachment]);
    }

    public function hasAttached(string $class): bool
    {
        if (!\class_exists($class)) {
            return false;
        }

        return $this->attachments->filter(
            fn (DatasetEntityInterface $entity): bool => $entity instanceof $class
        )->valid();
    }

    public function getAttachment(string $class): ?DatasetEntityInterface
    {
        if (!\class_exists($class)) {
            return null;
        }

        $iterator = $this->attachments->filter(
            fn (DatasetEntityInterface $entity): bool => $entity instanceof $class
        );

        return $iterator->valid() ? $iterator->current() : null;
    }

    public function unattach(string $class): void
    {
        if (!\class_exists($class)) {
            return;
        }

        /** @var array<array-key, DatasetEntityInterface> $newItems */
        $newItems = iterable_to_array($this->attachments->filter(
            fn (DatasetEntityInterface $entity): bool => !$entity instanceof $class)
        );
        $this->attachments = new AttachmentCollection($newItems);
    }
}
