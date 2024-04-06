<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

final class IdentityErrorCreatePayload implements CreatePayloadInterface, AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private MappingComponentStructContract $mappingComponent,
        private \Throwable $throwable
    ) {
    }

    public function getMappingComponent(): MappingComponentStructContract
    {
        return $this->mappingComponent;
    }

    public function setMappingComponent(MappingComponentStructContract $mappingComponent): void
    {
        $this->mappingComponent = $mappingComponent;
    }

    public function getThrowable(): \Throwable
    {
        return $this->throwable;
    }

    public function setThrowable(\Throwable $throwable): void
    {
        $this->throwable = $throwable;
    }
}
