<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

/**
 * @deprecated and will be removed in 0.10 as it has not been a practical solution to defer closure execution in a different process
 */
interface DeferralAwareInterface
{
    /**
     * @deprecated see interface comment
     */
    public function defer(callable $fn): void;

    /**
     * @deprecated see interface comment
     */
    public function copyDeferrals(DeferralAwareInterface $target): void;

    /**
     * @deprecated see interface comment
     */
    public function resolveDeferrals(): void;
}
