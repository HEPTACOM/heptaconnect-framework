<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\FileReference\RequestPersist;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\FileReferenceRequestKeyInterface;

final class FileReferencePersistRequestResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    /**
     * @var array<string, FileReferenceRequestKeyInterface>
     */
    private array $fileReferenceRequestKeys = [];

    public function __construct(PortalNodeKeyInterface $portalNodeKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function addFileReferenceRequestKey(
        string $key,
        FileReferenceRequestKeyInterface $fileReferenceRequestKey
    ): void {
        $this->fileReferenceRequestKeys[$key] = $fileReferenceRequestKey;
    }

    public function getFileReferenceRequestKey(string $key): ?FileReferenceRequestKeyInterface
    {
        return $this->fileReferenceRequestKeys[$key] ?? null;
    }

    /**
     * @return FileReferenceRequestKeyInterface[]
     */
    public function getFileReferenceRequestKeys(): array
    {
        return $this->fileReferenceRequestKeys;
    }
}
