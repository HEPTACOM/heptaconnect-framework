<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\ReadException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface PortalNodeConfigurationGetActionInterface
{
    /**
     * @throws ReadException
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<PortalNodeConfigurationGetResult>
     */
    public function get(PortalNodeConfigurationGetCriteria $criteria): iterable;
}
