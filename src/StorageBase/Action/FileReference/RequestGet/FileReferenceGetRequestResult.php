<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\FileReferenceRequestKeyInterface;

final class FileReferenceGetRequestResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    private FileReferenceRequestKeyInterface $requestKey;

    private string $serializedRequest;

    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        FileReferenceRequestKeyInterface $requestKey,
        string $serializedRequest
    ) {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
        $this->requestKey = $requestKey;
        $this->serializedRequest = $serializedRequest;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getRequestKey(): FileReferenceRequestKeyInterface
    {
        return $this->requestKey;
    }

    public function getSerializedRequest(): string
    {
        return $this->serializedRequest;
    }
}
