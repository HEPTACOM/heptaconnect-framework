<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\FileReferenceRequestKeyInterface;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

final class FileReferenceGetRequestResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey,
        private FileReferenceRequestKeyInterface $requestKey,
        private string $serializedRequest
    ) {
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
