<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

final class NoMatchForPackageQueryException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    private string $query;

    public function __construct(string $query, int $code, ?\Throwable $previous = null)
    {
        parent::__construct(\sprintf('The given package query "%s" did not match anything', $query), $code, $previous);
        $this->query = $query;
    }

    public function getQuery(): string
    {
        return $this->query;
    }
}
