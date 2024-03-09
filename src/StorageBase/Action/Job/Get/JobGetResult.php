<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Get;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;

final class JobGetResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private string $jobType,
        private JobKeyInterface $jobKey,
        private MappingComponentStruct $mappingComponent,
        private ?array $payload
    ) {
    }

    public function getJobType(): string
    {
        return $this->jobType;
    }

    public function getJobKey(): JobKeyInterface
    {
        return $this->jobKey;
    }

    public function getMappingComponent(): MappingComponentStruct
    {
        return $this->mappingComponent;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }
}
