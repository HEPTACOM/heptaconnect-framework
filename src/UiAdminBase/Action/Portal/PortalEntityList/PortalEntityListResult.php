<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalEntityListResult implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    private CodeOrigin $codeOrigin;

    private EntityType $supportedEntityType;

    /**
     * @var class-string
     */
    private string $flowComponentClass;

    /**
     * @param class-string $flowComponentClass
     */
    public function __construct(
        CodeOrigin $codeOrigin,
        EntityType $supportedEntityType,
        string $flowComponentClass
    ) {
        $this->attachments = new AttachmentCollection();
        $this->codeOrigin = $codeOrigin;
        $this->supportedEntityType = $supportedEntityType;
        $this->flowComponentClass = $flowComponentClass;
    }

    public function getCodeOrigin(): CodeOrigin
    {
        return $this->codeOrigin;
    }

    public function getSupportedEntityType(): EntityType
    {
        return $this->supportedEntityType;
    }

    /**
     * @return class-string
     */
    public function getFlowComponentClass(): string
    {
        return $this->flowComponentClass;
    }

    public function getAuditableData(): array
    {
        return [
            'codeOrigin' => $this->getCodeOrigin(),
            'supportedEntityType' => $this->getSupportedEntityType(),
            'flowComponentClass' => $this->getFlowComponentClass(),
        ];
    }
}
