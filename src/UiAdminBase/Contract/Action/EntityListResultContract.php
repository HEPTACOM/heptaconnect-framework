<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

/**
 * Describes a result item for requests on entity type support actions.
 */
abstract class EntityListResultContract implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @param class-string $flowComponentClass
     */
    public function __construct(
        private CodeOrigin $codeOrigin,
        private EntityType $supportedEntityType,
        private string $flowComponentClass
    ) {
    }

    /**
     * Gets the code origin of the flow component of the supported entity type.
     */
    public function getCodeOrigin(): CodeOrigin
    {
        return $this->codeOrigin;
    }

    /**
     * Gets the supported entity type of the flow component.
     */
    public function getSupportedEntityType(): EntityType
    {
        return $this->supportedEntityType;
    }

    /**
     * Gets the class of the flow component.
     *
     * @return class-string
     */
    public function getFlowComponentClass(): string
    {
        return $this->flowComponentClass;
    }

    public function getAuditableData(): array
    {
        return [
            'codeOrigin' => (string) $this->getCodeOrigin(),
            'supportedEntityType' => $this->getSupportedEntityType(),
            'flowComponentClass' => $this->getFlowComponentClass(),
        ];
    }
}
