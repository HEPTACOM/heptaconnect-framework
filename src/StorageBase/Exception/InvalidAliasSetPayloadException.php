<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Set\PortalNodeAliasSetPayload;

class InvalidAliasSetPayloadException extends CreateException
{
    private PortalNodeAliasSetPayload $payload;

    public function __construct(PortalNodeAliasSetPayload $payload, int $code, ?\Throwable $throwable = null)
    {
        parent::__construct($code, $throwable);

        $this->message = 'Set payload cannot be processed as it contains invalid values';
        $this->payload = $payload;
    }

    public function getPayload(): PortalNodeAliasSetPayload
    {
        return $this->payload;
    }
}
