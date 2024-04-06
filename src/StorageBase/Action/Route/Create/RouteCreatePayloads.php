<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Contract\AttachmentAwareInterface;

/**
 * @extends AbstractObjectCollection<RouteCreatePayload>
 */
final class RouteCreatePayloads extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @psalm-return RouteCreatePayload::class
     */
    protected function getT(): string
    {
        return RouteCreatePayload::class;
    }
}
