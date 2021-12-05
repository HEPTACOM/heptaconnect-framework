<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

interface DeferralAwareInterface
{
    public function defer(callable $fn): void;

    public function copyDeferrals(DeferralAwareInterface $target): void;

    public function resolveDeferrals(): void;
}
