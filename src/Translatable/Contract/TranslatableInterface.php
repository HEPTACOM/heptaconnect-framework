<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\Contract;

/**
 * @template T
 */
interface TranslatableInterface
{
    /**
     * @return T|null
     */
    public function getTranslation(string $localeKey);

    /**
     * @psalm-param T|null $value
     *
     * @param T|null $value
     */
    public function setTranslation(string $localeKey, $value): self;

    /**
     * @return array<int, string>
     */
    public function getLocaleKeys(): array;
}
