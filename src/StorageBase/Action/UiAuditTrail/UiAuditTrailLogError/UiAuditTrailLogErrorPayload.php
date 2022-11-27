<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogError;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\UiAuditTrailKeyInterface;

final class UiAuditTrailLogErrorPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private \DateTimeImmutable $at;

    /**
     * @param class-string<\Throwable> $exceptionClass
     */
    public function __construct(
        private UiAuditTrailKeyInterface $uiAuditTrailKey,
        private string $exceptionClass,
        private int $depth,
        private string $message,
        private string $code
    ) {
        $this->attachments = new AttachmentCollection();
        $this->at = new \DateTimeImmutable();
    }

    public function getAt(): \DateTimeImmutable
    {
        return $this->at;
    }

    public function setAt(\DateTimeImmutable $at): void
    {
        $this->at = $at;
    }

    public function getUiAuditTrailKey(): UiAuditTrailKeyInterface
    {
        return $this->uiAuditTrailKey;
    }

    public function setUiAuditTrailKey(UiAuditTrailKeyInterface $uiAuditTrailKey): void
    {
        $this->uiAuditTrailKey = $uiAuditTrailKey;
    }

    /**
     * @return class-string<\Throwable>
     */
    public function getExceptionClass(): string
    {
        return $this->exceptionClass;
    }

    /**
     * @param class-string<\Throwable> $exceptionClass
     */
    public function setExceptionClass(string $exceptionClass): void
    {
        $this->exceptionClass = $exceptionClass;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function setDepth(int $depth): void
    {
        $this->depth = $depth;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}
