<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;

trait AttachmentAwareTrait
{
    protected AttachmentCollection $attachments;

    public function getAttachments(): AttachmentCollection
    {
        return $this->attachments;
    }

    public function attach(AttachableInterface $attachment): void
    {
        $className = \get_class($attachment);
        $attachments = new AttachmentCollection($this->attachments->filter(
            fn (AttachableInterface $attachment): bool => \get_class($attachment) !== $className
        ));
        $attachments->push([$attachment]);

        $this->attachments = $attachments;
    }

    public function hasAttached(string $class): bool
    {
        if (!\class_exists($class) && !\interface_exists($class)) {
            return false;
        }

        return $this->attachments->filter(
            fn (AttachableInterface $attachment): bool => $attachment instanceof $class
        )->valid();
    }

    public function isAttached(AttachableInterface $attachable): bool
    {
        return $this->attachments->filter(
            fn (AttachableInterface $attachment): bool => $attachment === $attachable
        )->valid();
    }

    public function getAttachment(string $class): ?AttachableInterface
    {
        if (!\class_exists($class) && !\interface_exists($class)) {
            return null;
        }

        $iterator = $this->attachments->filter(
            fn (AttachableInterface $attachment): bool => \get_class($attachment) === $class
        );

        if ($iterator->valid() && ($attachment = $iterator->current()) instanceof $class) {
            return $attachment;
        }

        $iterator = $this->attachments->filter(
            fn (AttachableInterface $attachment): bool => $attachment instanceof $class
        );

        return $iterator->valid() ? $iterator->current() : null;
    }

    /**
     * @deprecated Use detachByType instead. Will be removed in 0.10
     *
     * @param class-string $class
     */
    public function unattach(string $class): void
    {
        $this->detachByType($class);
    }

    public function detachByType(string $class): void
    {
        if (!\class_exists($class) && !\interface_exists($class)) {
            return;
        }

        $this->attachments = new AttachmentCollection($this->attachments->filter(
            fn (AttachableInterface $attachment): bool => !$attachment instanceof $class
        ));
    }

    public function detach(AttachableInterface $attachable): void
    {
        $this->attachments = new AttachmentCollection($this->attachments->filter(
            fn (AttachableInterface $attachment): bool => $attachment !== $attachable
        ));
    }
}
