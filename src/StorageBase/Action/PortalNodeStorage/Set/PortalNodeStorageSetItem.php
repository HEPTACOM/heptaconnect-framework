<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

final class PortalNodeStorageSetItem implements CreatePayloadInterface, AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private string $storageKey;

    private string $type;

    private string $value;

    private ?\DateInterval $expiresIn;

    public function __construct(
        string $storageKey,
        string $value,
        string $type,
        ?\DateInterval $expiresIn
    ) {
        $this->attachments = new AttachmentCollection();
        $this->storageKey = $storageKey;
        $this->type = $type;
        $this->value = $value;
        $this->expiresIn = $expiresIn;
    }

    public function getStorageKey(): string
    {
        return $this->storageKey;
    }

    public function setStorageKey(string $storageKey): void
    {
        $this->storageKey = $storageKey;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getExpiresIn(): ?\DateInterval
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(?\DateInterval $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }
}
