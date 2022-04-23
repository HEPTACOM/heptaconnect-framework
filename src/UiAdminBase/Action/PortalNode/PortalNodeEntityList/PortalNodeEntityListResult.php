<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;

final class PortalNodeEntityListResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private CodeOrigin $codeOrigin;

    /**
     * @var class-string<DatasetEntityContract>
     */
    private string $supportedEntityType;

    /**
     * @var class-string
     */
    private string $flowComponentClass;

    /**
     * @param class-string<DatasetEntityContract> $supportedEntityType
     * @param class-string                        $flowComponentClass
     */
    public function __construct(CodeOrigin $codeOrigin, string $supportedEntityType, string $flowComponentClass)
    {
        $this->attachments = new AttachmentCollection();
        $this->codeOrigin = $codeOrigin;
        $this->supportedEntityType = $supportedEntityType;
        $this->flowComponentClass = $flowComponentClass;
    }

    public function getCodeOrigin(): CodeOrigin
    {
        return $this->codeOrigin;
    }

    /**
     * @return class-string<DatasetEntityContract>
     */
    public function getSupportedEntityType(): string
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
