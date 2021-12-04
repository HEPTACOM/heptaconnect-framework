<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Repository;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobPayloadKeyInterface;

class JobAdd implements JobInterface
{
    protected string $jobType;

    protected MappingComponentStructContract $mapping;

    protected ?JobPayloadKeyInterface $payloadKey;

    public function __construct(
        string $jobType,
        MappingComponentStructContract $mapping,
        ?JobPayloadKeyInterface $payloadKey = null
    ) {
        $this->jobType = $jobType;
        $this->mapping = $mapping;
        $this->payloadKey = $payloadKey;
    }

    public function getJobType(): string
    {
        return $this->jobType;
    }

    public function getMapping(): MappingComponentStructContract
    {
        return $this->mapping;
    }

    public function getPayloadKey(): ?JobPayloadKeyInterface
    {
        return $this->payloadKey;
    }
}
