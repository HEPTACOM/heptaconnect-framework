<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration;

use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Set\WebHttpHandlerConfigurationSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidCreatePayloadException;

interface WebHttpHandlerConfigurationSetActionInterface
{
    /**
     * @throws InvalidCreatePayloadException
     */
    public function set(WebHttpHandlerConfigurationSetPayloads $payloads): void;
}
