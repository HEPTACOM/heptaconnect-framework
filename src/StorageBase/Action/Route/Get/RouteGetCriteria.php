<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Get;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection;

final class RouteGetCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private RouteKeyCollection $routeKeys;

    public function __construct(RouteKeyCollection $routeKeys)
    {
        $this->attachments = new AttachmentCollection();
        $this->routeKeys = $routeKeys;
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
