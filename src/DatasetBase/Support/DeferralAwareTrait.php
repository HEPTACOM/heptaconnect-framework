<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DeferralAwareInterface;
use Opis\Closure\SerializableClosure;

trait DeferralAwareTrait
{
    /**
     * @var SerializableClosure[]
     */
    private array $deferrals = [];

    public function defer(callable $fn): void
    {
        $this->deferrals[] = new SerializableClosure(static fn () => $fn());
    }

    public function copyDeferrals(DeferralAwareInterface $target): void
    {
        foreach ($this->deferrals as $deferral) {
            $target->defer($deferral);
        }
    }

    public function resolveDeferrals(): void
    {
        foreach ($this->deferrals as $key => $deferral) {
            try {
                $deferral->getClosure()($this);
            } finally {
                unset($this->deferrals[$key]);
            }
        }
    }
}
