<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;

final class PortalEntityListResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private CodeOrigin $codeOrigin;

    private ClassStringReferenceContract $supportedEntityType;

    /**
     * @var class-string
     */
    private string $flowComponentClass;

    /**
     * @param class-string $flowComponentClass
     */
    public function __construct(
        CodeOrigin $codeOrigin,
        ClassStringReferenceContract $supportedEntityType,
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

    public function getSupportedEntityType(): ClassStringReferenceContract
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
}
