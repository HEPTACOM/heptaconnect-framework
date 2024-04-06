<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map;

use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

final class IdentityMapResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private MappedDatasetEntityCollection $mappedDatasetEntityCollection
    ) {
    }

    public function getMappedDatasetEntityCollection(): MappedDatasetEntityCollection
    {
        return $this->mappedDatasetEntityCollection;
    }
}
