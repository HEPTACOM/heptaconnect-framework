<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract;

use Heptacom\HeptaConnect\Portal\Base\Cronjob\Exception\InvalidCronExpressionException;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

/**
 * @internal
 */
interface CronjobServiceInterface
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobHandlerContract> $cronjobHandler
     *
     * @throws InvalidCronExpressionException
     */
    public function register(
        PortalNodeKeyInterface $portalNodeKey,
        string $cronjobHandler,
        string $cronExpression,
        ?array $payload = null
    ): CronjobInterface;

    public function unregister(CronjobKeyInterface $cronjobKey): void;
}
