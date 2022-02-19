<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;

final class IdentityMapResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private MappedDatasetEntityCollection $mappedDatasetEntityCollection;

    public function __construct(MappedDatasetEntityCollection $mappedDatasetEntityCollection)
    {
        $this->mappedDatasetEntityCollection = $mappedDatasetEntityCollection;
    }

    public function getMappedDatasetEntityCollection(): MappedDatasetEntityCollection
    {
        return $this->mappedDatasetEntityCollection;
    }
}
