<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

class EmptyAliasStringException extends \Exception
{
    public function __construct(?\Throwable $throwable = null)
    {
        parent::__construct($throwable);

        $this->message = 'Empty string is not a valid alias';
    }
}
