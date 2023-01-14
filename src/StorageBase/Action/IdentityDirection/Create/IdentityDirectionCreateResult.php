<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityDirection\Create;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\IdentityDirectionKeyInterface;

final class IdentityDirectionCreateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected IdentityDirectionKeyInterface $identityDirectionKey;

    public function __construct(IdentityDirectionKeyInterface $identityDirectionKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->identityDirectionKey = $identityDirectionKey;
    }

    public function getIdentityDirectionKey(): IdentityDirectionKeyInterface
    {
        return $this->identityDirectionKey;
    }
}
