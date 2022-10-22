<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\IdentityErrorKeyInterface;

final class IdentityErrorCreateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private IdentityErrorKeyInterface $identityErrorKey;

    public function __construct(IdentityErrorKeyInterface $identityErrorKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->identityErrorKey = $identityErrorKey;
    }

    public function getIdentityErrorKey(): IdentityErrorKeyInterface
    {
        return $this->identityErrorKey;
    }
}
