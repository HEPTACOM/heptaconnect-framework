<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

/**
 * @internal
 */
interface CronjobInterface
{
    public function getPortalNodeKey(): PortalNodeKeyInterface;

    public function getCronjobKey(): CronjobKeyInterface;

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobHandlerContract>
     */
    public function getHandler(): string;

    public function getPayload(): ?array;

    public function getCronExpression(): string;

    public function getQueuedUntil(): \DateTimeInterface;
}
