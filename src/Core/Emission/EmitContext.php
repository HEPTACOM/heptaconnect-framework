<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Emission;

use Heptacom\HeptaConnect\Core\Portal\AbstractPortalNodeContext;
use Heptacom\HeptaConnect\Core\Portal\Contract\PortalNodeContainerFacadeContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityError\Create\IdentityErrorCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityError\IdentityErrorCreateActionInterface;

final class EmitContext extends AbstractPortalNodeContext implements EmitContextInterface
{
    public function __construct(
        PortalNodeContainerFacadeContract $containerFacade,
        ?array $configuration,
        private IdentityErrorCreateActionInterface $identityErrorCreateAction,
        private bool $directEmission
    ) {
        parent::__construct($containerFacade, $configuration);
    }

    public function isDirectEmission(): bool
    {
        return $this->directEmission;
    }

    public function markAsFailed(string $externalId, string $entityType, \Throwable $throwable): void
    {
        $mappingComponent = new MappingComponentStruct($this->getPortalNodeKey(), new EntityType($entityType), $externalId);
        $payload = new IdentityErrorCreatePayloads([new IdentityErrorCreatePayload($mappingComponent, $throwable)]);

        $this->identityErrorCreateAction->create($payload);
    }
}
