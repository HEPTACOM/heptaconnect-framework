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
        $attachments = $this->attachments->filter(
            fn (AttachableInterface $attachment): bool => \get_class($attachment) !== $className
        );
        $attachments->push([$attachment]);

        $this->attachments = $attachments;
    }

    public function hasAttached(string $class): bool
    {
        if (!\class_exists($class) && !\interface_exists($class)) {
            return false;
        }

        return !$this->attachments
            ->filter(static fn (AttachableInterface $attachment): bool => $attachment instanceof $class)
            ->isEmpty();
    }

    public function isAttached(AttachableInterface $attachable): bool
    {
        return $this->attachments->contains($attachable);
    }

    public function getAttachment(string $class): ?AttachableInterface
    {
        if (!\class_exists($class) && !\interface_exists($class)) {
            return null;
        }

        $attachments = $this->attachments->filter(
            fn (AttachableInterface $attachment): bool => \get_class($attachment) === $class
        );

        if (!$attachments->isEmpty() && ($attachment = $attachments->first()) instanceof $class) {
            return $attachment;
        }

        return $this->attachments
            ->filter(fn (AttachableInterface $attachment): bool => $attachment instanceof $class)
            ->first();
    }

    public function detachByType(string $class): void
    {
        if (!\class_exists($class) && !\interface_exists($class)) {
            return;
        }

        $this->attachments = $this->attachments->filter(
            fn (AttachableInterface $attachment): bool => !$attachment instanceof $class
        );
    }

    public function detach(AttachableInterface $attachable): void
    {
        $this->attachments = $this->attachments->filter(
            fn (AttachableInterface $attachment): bool => $attachment !== $attachable
        );
    }
}
