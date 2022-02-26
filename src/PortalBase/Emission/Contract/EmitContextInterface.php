<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;

/**
 * Describes emission specific contexts.
 * @see EmitterContract
 * @see EmitterStackInterface
 */
interface EmitContextInterface extends PortalNodeContextInterface
{
    /**
     * Returns true, when this emitter decorates a direct emission started from an explorer.
     */
    public function isDirectEmission(): bool;

    /**
     * Store an exception attached to the given identity to be reviewed later.
     */
    public function markAsFailed(string $externalId, string $entityType, \Throwable $throwable): void;
}
