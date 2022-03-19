<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration;

use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Set\WebHttpHandlerConfigurationSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidCreatePayloadException;

interface WebHttpHandlerConfigurationSetActionInterface
{
    /**
     * Set web http handler configuration by portal node and path.
     *
     * @throws InvalidCreatePayloadException
     */
    public function set(WebHttpHandlerConfigurationSetPayloads $payloads): void;
}
