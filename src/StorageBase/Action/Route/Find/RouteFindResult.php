<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Find;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;

final class RouteFindResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private RouteKeyInterface $routeKey;

    public function __construct(RouteKeyInterface $routeKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->routeKey = $routeKey;
    }

    public function getRouteKey(): RouteKeyInterface
    {
        return $this->routeKey;
    }
}
