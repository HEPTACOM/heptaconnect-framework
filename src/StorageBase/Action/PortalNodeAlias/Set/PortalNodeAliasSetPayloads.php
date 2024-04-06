<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Set;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Contract\AttachmentAwareInterface;

/**
 * @extends AbstractObjectCollection<PortalNodeAliasSetPayload>
 */
final class PortalNodeAliasSetPayloads extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected function getT(): string
    {
        return PortalNodeAliasSetPayload::class;
    }
}
