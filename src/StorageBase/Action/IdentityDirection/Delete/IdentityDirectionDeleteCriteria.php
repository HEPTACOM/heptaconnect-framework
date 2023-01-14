<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityDirection\Delete;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\IdentityDirectionKeyCollection;

final class IdentityDirectionDeleteCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private IdentityDirectionKeyCollection $identityDirectionKeys;

    public function __construct(IdentityDirectionKeyCollection $identityDirectionKeys)
    {
        $this->attachments = new AttachmentCollection();
        $this->identityDirectionKeys = $identityDirectionKeys;
    }

    public function getIdentityDirectionKeys(): IdentityDirectionKeyCollection
    {
        return $this->identityDirectionKeys;
    }

    public function setIdentityDirectionKeys(IdentityDirectionKeyCollection $identityDirectionKeys): void
    {
        $this->identityDirectionKeys = $identityDirectionKeys;
    }
}
