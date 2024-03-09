<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;

trait AttachmentAwareTrait
{
    protected ?AttachmentCollection $attachments = null;

    public function getAttachments(): AttachmentCollection
    {
        return $this->attachments ??= new AttachmentCollection();
    }

    public function attach(AttachableInterface $attachment): void
    {
        $className = $attachment::class;
        $attachments = $this->getAttachments()->filter(
            fn (AttachableInterface $attachment): bool => $attachment::class !== $className
        );
        $attachments->push([$attachment]);

        $this->attachments = $attachments;
    }

    public function hasAttached(string $class): bool
    {
        if (!\class_exists($class) && !\interface_exists($class)) {
            return false;
        }

        return !$this->getAttachments()
            ->filter(static fn (AttachableInterface $attachment): bool => $attachment instanceof $class)
            ->isEmpty();
    }

    public function isAttached(AttachableInterface $attachable): bool
    {
        return $this->getAttachments()->contains($attachable);
    }

    public function getAttachment(string $class): ?AttachableInterface
    {
        if (!\class_exists($class) && !\interface_exists($class)) {
            return null;
        }

        $attachments = $this->getAttachments()->filter(
            fn (AttachableInterface $attachment): bool => $attachment::class === $class
        );

        if (!$attachments->isEmpty() && ($attachment = $attachments->first()) instanceof $class) {
            return $attachment;
        }

        return $this->getAttachments()
            ->filter(fn (AttachableInterface $attachment): bool => $attachment instanceof $class)
            ->first();
    }

    public function detachByType(string $class): void
    {
        if (!\class_exists($class) && !\interface_exists($class)) {
            return;
        }

        $this->attachments = $this->getAttachments()->filter(
            fn (AttachableInterface $attachment): bool => !$attachment instanceof $class
        );
    }

    public function detach(AttachableInterface $attachable): void
    {
        $this->attachments = $this->getAttachments()->filter(
            fn (AttachableInterface $attachment): bool => $attachment !== $attachable
        );
    }
}
