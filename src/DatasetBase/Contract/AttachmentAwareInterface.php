<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;

interface AttachmentAwareInterface
{
    public function getAttachments(): AttachmentCollection;

    public function attach(AttachableInterface $attachment): void;

    /**
     * @param class-string<AttachableInterface> $class
     */
    public function hasAttached(string $class): bool;

    /**
     * @param class-string<AttachableInterface> $class
     */
    public function getAttachment(string $class): ?AttachableInterface;

    /**
     * @param class-string<AttachableInterface> $class
     */
    public function unattach(string $class): void;
}
