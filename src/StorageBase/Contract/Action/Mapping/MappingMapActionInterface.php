<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Mapping;

use Heptacom\HeptaConnect\Storage\Base\Action\Mapping\Map\MappingMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Mapping\Map\MappingMapResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface MappingMapActionInterface
{
    /**
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function map(MappingMapPayload $payload): MappingMapResult;
}
