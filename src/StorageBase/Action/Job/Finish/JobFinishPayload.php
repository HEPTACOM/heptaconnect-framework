<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;

final class JobFinishPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private JobKeyCollection $jobKeys;

    private \DateTimeInterface $createdAt;

    private ?string $message;

    public function __construct(
        JobKeyCollection $jobKeys,
        \DateTimeInterface $createdAt,
        ?string $message
    ) {
        $this->attachments = new AttachmentCollection();
        $this->jobKeys = $jobKeys;
        $this->createdAt = $createdAt;
        $this->message = $message;
    }

    public function getJobKeys(): JobKeyCollection
    {
        return $this->jobKeys;
    }

    public function setJobKeys(JobKeyCollection $jobKeys): void
    {
        $this->jobKeys = $jobKeys;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}
