<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\IdentityErrorKeyInterface;
use Heptacom\HeptaConnect\Utility\Contract\AttachmentAwareInterface;

final class IdentityErrorCreateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private IdentityErrorKeyInterface $identityErrorKey
    ) {
    }

    public function getIdentityErrorKey(): IdentityErrorKeyInterface
    {
        return $this->identityErrorKey;
    }
}
