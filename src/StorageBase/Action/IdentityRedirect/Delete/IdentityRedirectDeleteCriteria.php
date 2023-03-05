<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Delete;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\IdentityRedirectKeyCollection;

final class IdentityRedirectDeleteCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private IdentityRedirectKeyCollection $identityRedirectKeys
    ) {
        $this->attachments = new AttachmentCollection();
    }

    public function getIdentityRedirectKeys(): IdentityRedirectKeyCollection
    {
        return $this->identityRedirectKeys;
    }

    public function setIdentityRedirectKeys(IdentityRedirectKeyCollection $identityRedirectKeys): void
    {
        $this->identityRedirectKeys = $identityRedirectKeys;
    }
}
