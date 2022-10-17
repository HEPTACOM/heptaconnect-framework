<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;

final class ReceptionRouteListResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(private RouteKeyInterface $routeKey)
    {
        $this->attachments = new AttachmentCollection();
    }

    public function getRouteKey(): RouteKeyInterface
    {
        return $this->routeKey;
    }
}
