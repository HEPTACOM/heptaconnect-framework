<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Create;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

final class JobCreatePayload implements CreatePayloadInterface, AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected string $jobType;

    protected MappingComponentStructContract $mapping;

    protected ?array $jobPayload;

    public function __construct(string $jobType, MappingComponentStructContract $mapping, ?array $jobPayload)
    {
        $this->jobType = $jobType;
        $this->mapping = $mapping;
        $this->jobPayload = $jobPayload;
    }

    public function getJobType(): string
    {
        return $this->jobType;
    }

    public function setJobType(string $jobType): void
    {
        $this->jobType = $jobType;
    }

    public function getMapping(): MappingComponentStructContract
    {
        return $this->mapping;
    }

    public function setMapping(MappingComponentStructContract $mapping): void
    {
        $this->mapping = $mapping;
    }

    public function getJobPayload(): ?array
    {
        return $this->jobPayload;
    }

    public function setJobPayload(?array $jobPayload): void
    {
        $this->jobPayload = $jobPayload;
    }
}
