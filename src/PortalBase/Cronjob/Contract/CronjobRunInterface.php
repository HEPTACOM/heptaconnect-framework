<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobRunKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

/**
 * @internal
 */
interface CronjobRunInterface
{
    public function getPortalNodeKey(): PortalNodeKeyInterface;

    public function getCronjobKey(): CronjobKeyInterface;

    public function getRunKey(): CronjobRunKeyInterface;

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobHandlerContract>
     */
    public function getHandler(): string;

    public function getPayload(): ?array;

    public function getQueuedFor(): \DateTimeInterface;
}
