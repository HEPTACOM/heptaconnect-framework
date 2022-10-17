<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

final class FileReferencePersistRequestPayload implements CreatePayloadInterface, AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var array<string, string>
     */
    private array $serializedRequests = [];

    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey
    ) {
        $this->attachments = new AttachmentCollection();
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    /**
     * @return string[]
     */
    public function getSerializedRequests(): array
    {
        return $this->serializedRequests;
    }

    public function addSerializedRequest(string $key, string $serializedRequest): void
    {
        $this->serializedRequests[$key] = $serializedRequest;
    }

    public function removeSerializedRequest(string $key): void
    {
        unset($this->serializedRequests[$key]);
    }
}
