<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestGet;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\FileReferenceRequestKeyCollection;

final class FileReferenceGetRequestCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    private FileReferenceRequestKeyCollection $fileReferenceRequestKeys;

    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        FileReferenceRequestKeyCollection $fileReferenceRequestKeys
    ) {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
        $this->fileReferenceRequestKeys = $fileReferenceRequestKeys;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getFileReferenceRequestKeys(): FileReferenceRequestKeyCollection
    {
        return $this->fileReferenceRequestKeys;
    }

    public function setFileReferenceRequestKeys(FileReferenceRequestKeyCollection $fileReferenceRequestKeys): void
    {
        $this->fileReferenceRequestKeys = $fileReferenceRequestKeys;
    }
}
