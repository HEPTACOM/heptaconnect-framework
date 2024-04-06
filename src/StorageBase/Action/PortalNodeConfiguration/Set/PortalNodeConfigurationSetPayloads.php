<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

/**
 * @extends AbstractObjectCollection<PortalNodeConfigurationSetPayload>
 */
final class PortalNodeConfigurationSetPayloads extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @psalm-return PortalNodeConfigurationSetPayload::class
     */
    protected function getT(): string
    {
        return PortalNodeConfigurationSetPayload::class;
    }
}
