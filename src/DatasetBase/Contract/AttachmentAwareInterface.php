<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;

/**
 * Describe an object, that can have attachments.
 * Objects with attachments are mainly stateful and often types to add structure to function arguments and return types like @see DatasetEntityContract
 */
interface AttachmentAwareInterface
{
    /**
     * Get all attached objects.
     */
    public function getAttachments(): AttachmentCollection;

    /**
     * Attach a new object and potentially remove a previously attached object of the same type.
     */
    public function attach(AttachableInterface $attachment): void;

    /**
     * Check whether an object of the class or interface is attached.
     *
     * @param class-string $class
     */
    public function hasAttached(string $class): bool;

    /**
     * Check whether the given object is attached.
     */
    public function isAttached(AttachableInterface $attachable): bool;

    /**
     * Gets an attached object of the class or interface.
     *
     * @param class-string $class
     */
    public function getAttachment(string $class): ?AttachableInterface;

    /**
     * Detach an attached object of the class or interface.
     *
     * @param class-string $class
     */
    public function detachByType(string $class): void;

    /**
     * Detach the given object, if it is attached
     */
    public function detach(AttachableInterface $attachable): void;
}
