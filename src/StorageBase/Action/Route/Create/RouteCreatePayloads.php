<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Create;

use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<RouteCreatePayload>
 */
final class RouteCreatePayloads extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    #[\Override]
    protected function getT(): string
    {
        return RouteCreatePayload::class;
    }
}
