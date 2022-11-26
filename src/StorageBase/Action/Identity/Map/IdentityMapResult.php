<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;

final class IdentityMapResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private MappedDatasetEntityCollection $mappedDatasetEntityCollection
    ) {
        $this->attachments = new AttachmentCollection();
    }

    public function getMappedDatasetEntityCollection(): MappedDatasetEntityCollection
    {
        return $this->mappedDatasetEntityCollection;
    }
}
