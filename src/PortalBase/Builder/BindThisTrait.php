<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

trait BindThisTrait
{
    private function bindThis(\Closure $closure): \Closure
    {
        $reflection = new \ReflectionFunction($closure);

        if (null === $reflection->getClosureThis()) {
            set_error_handler(static function () {});
            try {
                if ($c = \Closure::bind($closure, $this)) {
                    $closure = $c;
                }
            } finally {
                restore_error_handler();
            }
        }

        return $closure;
    }
}
