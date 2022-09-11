<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

trait BindThisTrait
{
    private function bindThis(\Closure $closure): \Closure
    {
        \set_error_handler(static function (): void {
        });

        try {
            if ($boundClosure = \Closure::bind($closure, $this)) {
                $closure = $boundClosure;
            }
        } finally {
            \restore_error_handler();
        }

        return $closure;
    }
}
