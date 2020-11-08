<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class MappingComponentStruct extends MappingComponentStructContract
{
    protected PortalNodeKeyInterface $portalNodeKey;

    /**
     * @psalm-var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    protected string $datasetEntityClassName;

    protected ?string $externalId = null;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $datasetEntityClassName
     */
    public function __construct(PortalNodeKeyInterface $portalNodeKey, string $datasetEntityClassName, ?string $externalId)
    {
        $this->portalNodeKey = $portalNodeKey;
        $this->datasetEntityClassName = $datasetEntityClassName;
        $this->externalId = $externalId;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function getDatasetEntityClassName(): string
    {
        return $this->datasetEntityClassName;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }
}
