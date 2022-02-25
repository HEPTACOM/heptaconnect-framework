<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration;

use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface WebHttpHandlerConfigurationFindActionInterface
{
    /**
     * Get web http handler configuration by portal node and path.
     * The configuration can be in the storage but is not expected.
     * Therefore we find a configuration instead of get one.
     *
     * @throws UnsupportedStorageKeyException
     */
    public function find(WebHttpHandlerConfigurationFindCriteria $criteria): WebHttpHandlerConfigurationFindResult;
}
