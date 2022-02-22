<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;

/**
 * @template T
 * @extends AbstractTranslatable<T>
 *
 * @property T $fallback
 */
abstract class AbstractTranslatableScalarCollection extends AbstractTranslatable
{
    public function __construct()
    {
        $this->fallback = $this->getInitialValue();
    }

    /**
     * @psalm-return T|null
     */
    public function getTranslation(string $localeKey, bool $returnFallback = false)
    {
        $result = parent::getTranslation($localeKey, false);

        if ($result !== null && $this->isValidValue($result)) {
            return $result;
        }

        if ($returnFallback) {
            return $this->getFallback();
        }

        $result = $this->getInitialValue();
        $this->setTranslation($localeKey, $result);

        return $result;
    }

    /**
     * @psalm-return T
     */
    public function getFallback()
    {
        return $this->fallback ??= $this->getInitialValue();
    }

    protected function isValidValue($value): bool
    {
        return $value instanceof $this->fallback;
    }

    /**
     * @psalm-return T&CollectionInterface
     */
    abstract protected function getInitialValue(): CollectionInterface;
}
