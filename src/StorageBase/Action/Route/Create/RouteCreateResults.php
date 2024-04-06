<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Create;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Contract\AttachmentAwareInterface;

/**
 * @extends AbstractObjectCollection<RouteCreateResult>
 */
final class RouteCreateResults extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @psalm-return RouteCreateResult::class
     */
    protected function getT(): string
    {
        return RouteCreateResult::class;
    }
}
