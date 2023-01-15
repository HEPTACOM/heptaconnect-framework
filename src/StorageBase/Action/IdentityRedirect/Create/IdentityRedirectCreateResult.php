<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Create;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\IdentityRedirectKeyInterface;

final class IdentityRedirectCreateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected IdentityRedirectKeyInterface $identityRedirectKey;

    public function __construct(IdentityRedirectKeyInterface $identityRedirectKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->identityRedirectKey = $identityRedirectKey;
    }

    public function getIdentityRedirectKey(): IdentityRedirectKeyInterface
    {
        return $this->identityRedirectKey;
    }
}
