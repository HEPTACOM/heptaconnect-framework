<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\Find;

use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface WebHttpHandlerConfigurationFindActionInterface
{
    /**
     * @throws UnsupportedStorageKeyException
     */
    public function find(WebHttpHandlerConfigurationFindCriteria $criteria): WebHttpHandlerConfigurationFindResult;
}
