<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;

/**
 * @template T
 * @template-extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection<T>
 */
abstract class AbstractObjectCollection extends AbstractCollection implements AttachableInterface
{
    abstract protected function getT(): string;

    /**
     * @psalm-param T $item
     */
    protected function isValidItem($item): bool
    {
        $expected = $this->getT();

        return \is_object($item) && $item instanceof $expected;
    }
}
