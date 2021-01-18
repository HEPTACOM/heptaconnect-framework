<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Support\Contract;

use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\TypeFilter;
use DeepCopy\TypeMatcher\TypeMatcher;

class DeepCloneContract
{
    /**
     * @psalm-template T
     * @psalm-param T $any
     * @psalm-return T
     *
     * @param mixed $any
     * @return mixed
     */
    public function deepClone($any)
    {
        $copier = new DeepCopy();
        $copier->addTypeFilter(new class ($copier) implements TypeFilter {
            private DeepCopy $copier;

            public function __construct(DeepCopy $copier)
            {
                $this->copier = $copier;
            }

            /**
             * @param \WeakReference $element
             * @return \WeakReference|null
             */
            public function apply($element)
            {
                $inner = $element->get();

                return \is_object($inner) ? \WeakReference::create($this->copier->copy($inner)) : null;
            }
        }, new TypeMatcher(\WeakReference::class));

        return $copier->copy($any);
    }
}
