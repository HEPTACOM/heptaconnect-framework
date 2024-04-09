<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Set;

use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<WebHttpHandlerConfigurationSetPayload>
 */
final class WebHttpHandlerConfigurationSetPayloads extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @psalm-return WebHttpHandlerConfigurationSetPayload::class
     */
    protected function getT(): string
    {
        return WebHttpHandlerConfigurationSetPayload::class;
    }
}
