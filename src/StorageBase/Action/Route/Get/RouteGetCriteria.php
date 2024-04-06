<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Get;

use Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

final class RouteGetCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private RouteKeyCollection $routeKeys
    ) {
    }

    public function getRouteKeys(): RouteKeyCollection
    {
        return $this->routeKeys;
    }

    public function setRouteKeys(RouteKeyCollection $routeKeys): void
    {
        $this->routeKeys = $routeKeys;
    }
}
