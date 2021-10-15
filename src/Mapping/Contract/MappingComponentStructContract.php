<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

abstract class MappingComponentStructContract implements \JsonSerializable
{
    use JsonSerializeObjectVarsTrait;

    abstract public function getPortalNodeKey(): PortalNodeKeyInterface;

    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    abstract public function getEntityType(): string;

    abstract public function getExternalId(): string;
}
