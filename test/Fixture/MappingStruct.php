<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Contract\MappingInterface;

class MappingStruct implements MappingInterface
{
    public function getExternalId(): string
    {
        return __METHOD__;
    }

    public function getPortalNodeId(): string
    {
        return __METHOD__;
    }

    public function getDatasetEntityClassName(): string
    {
        return __METHOD__;
    }
}
