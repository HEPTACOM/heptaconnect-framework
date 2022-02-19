<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set\PortalNodeConfigurationSetPayload>
 */
final class PortalNodeConfigurationSetPayloads extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected function getT(): string
    {
        return PortalNodeConfigurationSetPayload::class;
    }
}
