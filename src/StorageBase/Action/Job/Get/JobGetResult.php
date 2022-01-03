<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Get;

use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;

class JobGetResult
{
    protected string $jobType;

    protected JobKeyInterface $jobKey;

    private MappingComponentStruct $mappingComponent;

    private ?array $payload;

    public function __construct(
        string $jobType,
        JobKeyInterface $jobKey,
        MappingComponentStruct $mappingComponent,
        ?array $payload
    ) {
        $this->jobType = $jobType;
        $this->jobKey = $jobKey;
        $this->mappingComponent = $mappingComponent;
        $this->payload = $payload;
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
