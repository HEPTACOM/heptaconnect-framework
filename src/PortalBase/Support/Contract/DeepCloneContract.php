<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Support\Contract;

use DeepCopy\DeepCopy;

class DeepCloneContract
{
    /**
     * @template T
     *
     * @param T $any
     *
     * @return T
     */
    public function deepClone($any)
    {
        $copier = new DeepCopy();
        /** @var T $result */
        $result = $copier->copy($any);

        return $result;
    }
}
