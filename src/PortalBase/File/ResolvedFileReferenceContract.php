<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\File;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

abstract class ResolvedFileReferenceContract
{
    private PortalNodeKeyInterface $portalNodeKey;

    public function __construct(PortalNodeKeyInterface $portalNodeKey)
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    /**
     * Returns a public URI to retrieve the referenced file contents. At the time this URI is returned, a `GET` request
     * without any additional header lines **MUST** respond with the contents of the referenced file. At any later time
     * the URI **MAY** have been invalidated by arbitrary conditions. For this reason, the URI **SHOULD NOT** be trusted
     * to always respond with the referenced file contents.
     */
    abstract public function getPublicUrl(): string;

    /**
     * Returns the file contents of the referenced file as string.
     *
     * @throws \Throwable
     */
    abstract public function getContents(): string;

    protected function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }
}
