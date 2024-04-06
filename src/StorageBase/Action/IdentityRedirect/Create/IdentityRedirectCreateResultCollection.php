<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Contract\AttachmentAwareInterface;

/**
 * @extends AbstractObjectCollection<IdentityRedirectCreateResult>
 */
final class IdentityRedirectCreateResultCollection extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @psalm-return IdentityRedirectCreateResult::class
     */
    protected function getT(): string
    {
        return IdentityRedirectCreateResult::class;
    }
}
