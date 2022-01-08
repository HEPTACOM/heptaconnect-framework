<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration;

use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface WebHttpHandlerConfigurationFindActionInterface
{
    /**
     * @throws UnsupportedStorageKeyException
     */
    public function find(WebHttpHandlerConfigurationFindCriteria $criteria): WebHttpHandlerConfigurationFindResult;
}
