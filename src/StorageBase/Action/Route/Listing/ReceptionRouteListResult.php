<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing;

use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

final class ReceptionRouteListResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private RouteKeyInterface $routeKey
    ) {
    }

    public function getRouteKey(): RouteKeyInterface
    {
        return $this->routeKey;
    }
}
