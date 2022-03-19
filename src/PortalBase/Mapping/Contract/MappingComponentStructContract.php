<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

/**
 * Resembles all components of a mapping: what it is, which portal node it belongs to and which id it uses for identification.
 * Most importantly it does not need to know whether it is present in the storage @see MappingInterface
 */
abstract class MappingComponentStructContract implements \JsonSerializable
{
    use JsonSerializeObjectVarsTrait;

    /**
     * Get portal node of the mapping.
     */
    abstract public function getPortalNodeKey(): PortalNodeKeyInterface;

    /**
     * Get entity type of the mapping.
     *
     * @psalm-return class-string<DatasetEntityContract>
     */
    abstract public function getEntityType(): string;

    /**
     * Get the primary key used by the referenced identity.
     */
    abstract public function getExternalId(): string;
}
