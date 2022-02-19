<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;

interface AttachmentAwareInterface
{
    public function getAttachments(): AttachmentCollection;

    public function attach(AttachableInterface $attachment): void;

    /**
     * @param class-string $class
     */
    public function hasAttached(string $class): bool;

    public function isAttached(AttachableInterface $attachable): bool;

    /**
     * @param class-string $class
     */
    public function getAttachment(string $class): ?AttachableInterface;

    /**
     * @param class-string $class
     */
    public function detachByType(string $class): void;

    public function detach(AttachableInterface $attachable): void;
}
