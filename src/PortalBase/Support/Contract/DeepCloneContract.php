<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Support\Contract;

use DeepCopy\DeepCopy;

class DeepCloneContract
{
    /**
     * @psalm-template T
     * @psalm-param T $any
     * @psalm-return T
     *
     * @param mixed $any
     *
     * @return mixed
     */
    public function deepClone($any)
    {
        $copier = new DeepCopy();
        /** @var T $result */
        $result = $copier->copy($any);

        return $result;
    }
}
