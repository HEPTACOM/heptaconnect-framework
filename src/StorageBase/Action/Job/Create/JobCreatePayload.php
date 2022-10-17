<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Create;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

final class JobCreatePayload implements CreatePayloadInterface, AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(private string $jobType, private MappingComponentStructContract $mapping, private ?array $jobPayload)
    {
        $this->attachments = new AttachmentCollection();
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
