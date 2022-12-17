<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;

final class UnsupportedSortingException extends \UnexpectedValueException implements InvalidArgumentThrowableInterface
{
    public function __construct(
        private string $value,
        private StringCollection $availableValues,
        int $code,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf(
            'Value "%s" is not supported. Expected one value of "%s"',
            $this->value,
            $this->availableValues->join('", "')
        );

        parent::__construct($message, $code, $previous);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getAvailableValues(): StringCollection
    {
        return $this->availableValues;
    }
}
